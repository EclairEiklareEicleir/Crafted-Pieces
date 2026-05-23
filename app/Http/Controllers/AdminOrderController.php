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
    private const ACTIVE_ORDER_STATUSES = [
        'pending',
        'processing',
        'shipped',
        'out_for_delivery',
    ];

    private const ORDER_STATUSES = [
        'pending',
        'processing',
        'shipped',
        'out_for_delivery',
        'delivered',
        'received',
        'cancelled',
    ];

    private const ORDER_STATUS_NOTIFICATIONS = [
        'pending' => [
            'title' => 'Order Status Updated',
            'message' => 'Your order is now pending.',
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
        'cancelled' => [
            'title' => 'Order Cancelled',
            'message' => 'Your order has been cancelled.',
        ],
    ];

    // LIST + FILTER + SEARCH + PAGINATION
    public function index(Request $request)
    {
        $query = Order::query()
            ->whereIn('status', self::ACTIVE_ORDER_STATUSES)
            ->latest();

        // SEARCH (name/email/id)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        // STATUS FILTER
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    // SHOW SINGLE ORDER
    public function show(Order $order)
    {
        $order->load('items.product', 'items.productVariant', 'items.yarnColor');
        return view('admin.orders.show', compact('order'));
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
        $order->delete();
        return back()->with('success', 'Order deleted.');
    }

    // BULK ACTIONS
    public function bulkAction(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'action' => ['required', 'string', Rule::in(array_merge(['delete'], self::ORDER_STATUSES))],
        ]);

        $selectedOrders = Order::whereIn('id', $request->orders)->with('user')->get();

        switch ($request->action) {

            case 'delete':
                Order::whereIn('id', $request->orders)->delete();
                break;

            default:
                $selectedOrders->each(function (Order $order) use ($request) {
                    $this->applyStatusUpdate($order, $request->action);
                });
                break;
        }

        return back()->with('success', 'Bulk action completed.');
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

    private function applyStatusUpdate(Order $order, string $status): void
    {
        if ($order->status !== $status) {
            $order->update(['status' => $status]);
        }

        $this->notifyOrderCustomer($order, $status);
    }

    private function notifyOrderCustomer(Order $order, string $status): void
    {
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
