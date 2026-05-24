<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    private const ACTIVE_ORDER_STATUSES = Order::ACTIVE_ORDER_STATUSES;

    private const ORDER_STATUSES = Order::ORDER_STATUSES;

    private const PAYMENT_STATUSES = Order::PAYMENT_STATUSES;

    private const ORDER_STATUS_NOTIFICATIONS = [
        'pending' => [
            'title' => 'Order Status Updated',
            'message' => 'Your order is now pending.',
        ],
        'awaiting_payment' => [
            'title' => 'Order Awaiting Payment',
            'message' => 'Your order is awaiting payment.',
        ],
        'processing' => [
            'title' => 'Order Status Updated',
            'message' => 'Your order is now being processed.',
        ],
        'shipped' => [
            'title' => 'Order Shipped',
            'message' => 'Your order has been shipped.',
        ],
        'out_for_delivery' => [
            'title' => 'Order Out for Delivery',
            'message' => 'Your order is out for delivery.',
        ],
        'delivered' => [
            'title' => 'Order Delivered',
            'message' => 'Your order has been delivered.',
        ],
        'received' => [
            'title' => 'Order Received',
            'message' => 'Your order has been marked as received.',
        ],
        'completed' => [
            'title' => 'Order Completed',
            'message' => 'Your order has been completed.',
        ],
        'cancelled' => [
            'title' => 'Order Cancelled',
            'message' => 'Your order has been cancelled.',
        ],
        'refunded' => [
            'title' => 'Order Refunded',
            'message' => 'Your order has been refunded.',
        ],
        'rejected' => [
            'title' => 'Order Rejected',
            'message' => 'Your order has been rejected.',
        ],
        'failed' => [
            'title' => 'Order Failed',
            'message' => 'Your order could not be completed.',
        ],
    ];

    // LIST + FILTER + SEARCH + PAGINATION
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', Rule::in(self::ACTIVE_ORDER_STATUSES)],
            'payment_status' => ['nullable', 'string', Rule::in(self::PAYMENT_STATUSES)],
        ]);

        $search = trim((string) ($validated['search'] ?? ''));
        $status = trim((string) ($validated['status'] ?? ''));
        $paymentStatus = trim((string) ($validated['payment_status'] ?? ''));

        $query = Order::query()
            ->with('user')
            ->activeOrders()
            ->latest();

        // SEARCH (id/reference/name/email/status/payment status)
        if ($search !== '') {
            $normalizedSearch = strtolower(str_replace([' ', '-'], '_', $search));
            $numericSearch = ltrim($search, '#');

            $query->where(function ($q) use ($search, $normalizedSearch, $numericSearch) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('public_reference', 'like', "%{$search}%")
                  ->orWhere('status', $normalizedSearch)
                  ->orWhere('payment_status', $normalizedSearch)
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                  });

                if (ctype_digit($numericSearch)) {
                    $q->orWhere('id', (int) $numericSearch);
                }
            });
        }

        // STATUS FILTER
        if ($status !== '') {
            $query->where('status', $status);
        }

        // PAYMENT FILTER
        if ($paymentStatus !== '') {
            $query->where('payment_status', $paymentStatus);
        }

        $orders = $query->paginate(12)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'orderStatuses' => self::ACTIVE_ORDER_STATUSES,
            'paymentStatuses' => self::PAYMENT_STATUSES,
            'bulkStatuses' => self::ORDER_STATUSES,
        ]);
    }

    // SHOW SINGLE ORDER
    public function show(Order $order)
    {
        $order->load('items.product', 'items.productVariant', 'items.yarnColor', 'user');
        $order->loadMissing('customOrderRequest');

        return view('admin.orders.show', [
            'order' => $order,
            'orderStatuses' => self::ORDER_STATUSES,
        ]);
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => ['required', 'string', Rule::in(['pending', 'paid'])],
        ]);

        if (($order->order_type ?? 'online_order') !== 'walk_in_order') {
            return back()->withErrors(['payment_status' => 'Manual payment updates are only available for walk-in orders.']);
        }

        $paymentStatus = $request->string('payment_status')->toString();
        $updates = ['payment_status' => $paymentStatus];

        if ($paymentStatus === 'paid') {
            $updates['paid_at'] = now();
        } else {
            $updates['paid_at'] = null;
        }

        $order->update($updates);

        return back()->with('success', 'Payment status updated.');
    }

    // UPDATE SINGLE STATUS
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'string', Rule::in(self::ORDER_STATUSES)],
        ]);

        if ($order->status === $request->status) {
            return back()->with('success', 'Order status is already up to date.');
        }

        $this->applyStatusUpdate($order, $request->status);

        return back()->with('success', 'Order status updated.');
    }

    // DELETE SINGLE
    public function destroy(Order $order)
    {
        if ($order->isActiveOrder()) {
            return back()->with('error', 'Active orders cannot be deleted from this action.');
        }

        $order->delete();
        return back()->with('success', 'Order deleted.');
    }

    // BULK ACTIONS
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'orders' => ['required', 'array', 'min:1'],
            'orders.*' => ['integer', 'exists:orders,id'],
            'action' => ['required', 'string', Rule::in(self::ORDER_STATUSES)],
        ], [
            'orders.required' => 'Select at least one order before applying a bulk action.',
            'orders.min' => 'Select at least one order before applying a bulk action.',
            'action.required' => 'Choose a bulk action before applying it.',
            'action.in' => 'Active orders cannot be deleted from this bulk action.',
        ]);

        $selectedOrders = Order::activeOrders()
            ->whereIn('id', $validated['orders'])
            ->with('user')
            ->get();

        if ($selectedOrders->count() !== count(array_unique($validated['orders']))) {
            return back()->with('error', 'Only active orders can be updated from Active Orders.');
        }

        $updatedCount = 0;

        $selectedOrders->each(function (Order $order) use ($validated, &$updatedCount) {
            if ($this->applyStatusUpdate($order, $validated['action'])) {
                $updatedCount++;
            }
        });

        if ($updatedCount === 0) {
            return back()->with('success', 'No selected orders needed a status change.');
        }

        return back()->with('success', $updatedCount . ' selected order' . ($updatedCount === 1 ? '' : 's') . ' updated.');
    }

    // MANUAL ORDER INSERT (PROF REQUIREMENT)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string|max:50',
            'total_amount' => 'required|numeric|min:0',
        ]);

        Order::create($validated + [
            'order_type' => 'walk_in_order',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        return back()->with('success', 'Manual order created successfully.');
    }
    public function create()
    {
        return view('admin.orders.create');
    }

    public function downloadReceipt(Order $order)
    {
        if (! extension_loaded('gd')) {
            Log::error('Admin receipt PDF generation failed because the PHP GD extension is missing.', [
                'order_id' => $order->id,
                'php_binary' => PHP_BINARY,
            ]);

            abort(500, 'PDF receipts require the PHP GD extension. Enable extension=gd in C:\\xampp\\php\\php.ini and restart Apache or php artisan serve.');
        }

        $order->load('items.product', 'items.productVariant', 'items.yarnColor');
        $order->loadMissing('customOrderRequest');

        $pricing = [
            'subtotal' => $order->subtotal ?? $order->computed_subtotal,
            'platform_fee' => $order->platform_fee ?? 0,
            'delivery_fee' => $order->delivery_fee ?? 0,
            'vat' => $order->vat_amount ?? 0,
            'total' => $order->total_amount ?? 0,
        ];

        $pdf = Pdf::loadView('user.receipt.receipt-pdf', [
            'order' => $order,
            'pricing' => $pricing,
        ]);

        return $pdf->download('receipt-order-' . $order->id . '.pdf');
    }

    private function applyStatusUpdate(Order $order, string $status): bool
    {
        if ($order->status === $status) {
            return false;
        }

        $order->update(['status' => $status]);
        $this->notifyOrderCustomer($order, $status);

        return true;
    }

    private function notifyOrderCustomer(Order $order, string $status): void
    {
        if (! $order->user_id) {
            return;
        }

        $order->loadMissing('user');

        if (! $order->user) {
            return;
        }

        $notification = self::ORDER_STATUS_NOTIFICATIONS[$status] ?? null;

        if (! $notification) {
            return;
        }

        Notification::notifyUser($order->user, [
            'title' => $notification['title'],
            'message' => $notification['message'],
            'link' => route('orders.show', $order),
        ]);
    }
}
