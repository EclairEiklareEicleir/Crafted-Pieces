<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PricingService;

class OrderController extends Controller
{
    // =========================
    // RECEIPT
    // =========================
    public function downloadReceipt(Order $order)
    {
        if (Auth::id() !== $order->user_id) {
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
    public function show(Order $order)
    {
        if (Auth::id() !== $order->user_id) {
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
            'order_id' => 'required|integer',
            'email' => 'required|email'
        ]);

        $order = Order::with('items.product', 'items.productVariant', 'items.yarnColor')
            ->where('id', $request->order_id)
            ->where('email', $request->email)
            ->first();

        if (! $order) {
            return back()->withErrors([
                'error' => 'Order not found. Please check your details.'
            ]);
        }

        $pricing = (new PricingService())
            ->calculateFromOrder($order);

        return view('user.orders.track-result', [
            'order' => $order,
            'pricing' => $pricing
        ]);
    }
}
