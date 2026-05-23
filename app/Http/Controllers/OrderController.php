<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PricingService;
use Illuminate\Support\Facades\URL;

class OrderController extends Controller
{
    // =========================
    // RECEIPT
    // =========================
    public function downloadReceipt(Request $request, Order $order)
    {
        if (! $this->canAccessOrder($request, $order)) {
            abort(403);
        }

        if (! extension_loaded('gd')) {
            Log::error('Receipt PDF generation failed because the PHP GD extension is missing.', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'php_binary' => PHP_BINARY,
            ]);

            abort(500, 'PDF receipts require the PHP GD extension. Enable extension=gd in C:\\xampp\\php\\php.ini and restart Apache or php artisan serve.');
        }

        $order->load('items.product', 'items.productVariant', 'items.yarnColor');

        $pricing = (new PricingService())
            ->calculateFromOrder($order);

        $pdf = Pdf::loadView(
            'user.receipt.receipt-pdf',
            compact('order', 'pricing')
        );

        return $pdf->download('receipt-order-' . $order->id . '.pdf');
    }
    // =========================
    // MY ORDERS (LOGGED IN)
    // =========================
    public function index()
    {
        $orders = Order::with('items.product', 'items.productVariant', 'items.yarnColor')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.orders.index', compact('orders'));
    }

    // =========================
    // VIEW SINGLE ORDER (NEW)
    // =========================
    public function show(Request $request, Order $order)
    {
        if (! $this->canAccessOrder($request, $order)) {
            abort(403);
        }

        $order->load('items.product', 'items.productVariant', 'items.yarnColor');
        return view('user.orders.show', compact('order'));
    }

    // =========================
    // GUEST TRACK FORM
    // =========================
    public function trackForm()
    {
        return view('user.orders.track');
    }

    // =========================
    // GUEST TRACK RESULT
    // =========================
    public function track(Request $request)
    {
        $request->validate([
            'order_reference' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $orderReference = strtoupper(trim((string) $request->order_reference));

        $order = Order::with('items.product', 'items.productVariant', 'items.yarnColor')
            ->where('public_reference', $orderReference)
            ->where('email', $request->email)
            ->first();

        if (! $order) {
            return back()->withErrors([
                'order_reference' => 'Order not found. Please check your details.',
            ]);
        }

        $pricing = (new PricingService())
            ->calculateFromOrder($order);

        $receiptUrl = URL::temporarySignedRoute(
            'orders.receipt.download',
            now()->addDays(7),
            ['order' => $order]
        );

        return view('user.orders.track-result', [
            'order' => $order,
            'pricing' => $pricing,
            'receiptUrl' => $receiptUrl,
        ]);
    }

    private function canAccessOrder(Request $request, Order $order): bool
    {
        if (Auth::check() && $order->user_id === Auth::id()) {
            return true;
        }

        if (! Auth::check() && $order->user_id === null && $order->guest_session_id === $request->session()->getId()) {
            return true;
        }

        return $request->hasValidSignature();
    }
}
