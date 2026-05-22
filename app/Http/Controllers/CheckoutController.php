<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\CustomOrderRequest;
use App\Services\PayMongoService;
use App\Services\PricingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class CheckoutController extends Controller
{
    private function getCart(Request $request): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = $request->session()->getId() ?: $request->session()->token() ?: uniqid('session_', true);

        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    /*
    |--------------------------------------------------------------------------
    | CART CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $cart = $this->getCart($request);

        $cartItems = $cart
            ? $cart->items()->with(['product', 'productVariant', 'yarnColor'])->get()
            : collect();

        $pricing = (new PricingService)->calculate($cartItems);

        return view('user.checkout', compact('cartItems', 'pricing'));
    }

    public function submit(Request $request)
    {
        $cart = $this->getCart($request);
        $cartItems = $cart->items()->with(['product', 'productVariant', 'yarnColor'])->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors(['checkout' => 'Your cart is empty.']);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'shipping_address' => 'required|string',
        ]);

        $pricing = (new PricingService)->calculate($cartItems);
        $paymentMethod = 'PayMongo';

        $order = DB::transaction(function () use ($validated, $pricing, $cartItems, $paymentMethod) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'shipping_address' => $validated['shipping_address'],
            'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'subtotal' => $pricing['subtotal'],
                'platform_fee' => $pricing['platform_fee'],
                'delivery_fee' => $pricing['delivery_fee'],
                'vat_amount' => $pricing['vat'],
                'total_amount' => $pricing['total'],
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'yarn_color_id' => $item->yarn_color_id,
                    'variant_name' => $item->variant_name,
                    'variant_hex_color' => $item->variant_hex_color,
                    'variant_image_path' => $item->variant_image_path,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            return $order->load('items.product', 'items.productVariant', 'items.yarnColor');
        });

        try {
            $payMongoSession = app(PayMongoService::class)->createCheckoutSession($order);

            $order->update([
                'paymongo_checkout_id' => $payMongoSession['checkout_session_id'],
            ]);

            $cart->items()->delete();

            return redirect()->away($payMongoSession['checkout_url']);
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors(['checkout' => 'Unable to start PayMongo checkout right now. Please try again.']);
        }
    }

    public function success(Order $order)
    {
        $order->load('items.product', 'items.productVariant', 'items.yarnColor');

        return view('user.order-success', compact('order'));
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT
    |--------------------------------------------------------------------------
    */

    public function payment($type, $id)
    {
        $item = $this->resolvePaymentItem($type, $id);

        if (Auth::check() && $item->user_id !== Auth::id()) {
            abort(403);
        }

        if ($type === 'custom-order') {

            $basePrice = $item->final_price ?? $item->estimated_price;
            $pricing = (new PricingService)->calculateCustomOrder($basePrice);

        } else {
            $pricing = (new PricingService)->calculateFromOrder($item);
        }

        return view('user.payment', compact('type', 'item', 'pricing'));
    }

    public function processPayment(Request $request, $type, $id)
    {
        $item = $this->resolvePaymentItem($type, $id);

        if ($type === 'custom-order' && $item->paymentIsExpired()) {
            $item->update(['status' => CustomOrderRequest::STATUS_REJECTED]);
            return back()->withErrors(['payment' => 'Payment expired']);
        }

        if ($item->user_id !== Auth::id()) {
            abort(403);
        }

        $item->update([
            'status' => $type === 'custom-order'
                ? CustomOrderRequest::STATUS_PAID
                : 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route(
            $type === 'custom-order'
                ? 'custom-order.show'
                : 'checkout.success',
            $item
        )->with('success', 'Payment successful.');
    }

    /*
    |--------------------------------------------------------------------------
    | RESOLVER
    |--------------------------------------------------------------------------
    */

    private function resolvePaymentItem($type, $id)
    {
        return match ($type) {
            'custom-order' => CustomOrderRequest::findOrFail($id),
            'order' => Order::findOrFail($id),
            default => abort(404),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | RECEIPT
    |--------------------------------------------------------------------------
    */

    public function downloadCustomReceipt(CustomOrderRequest $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (! extension_loaded('gd')) {
            Log::error('Custom receipt PDF generation failed because the PHP GD extension is missing.', [
                'custom_order_id' => $order->id,
                'user_id' => Auth::id(),
                'php_binary' => PHP_BINARY,
            ]);

            abort(500, 'PDF receipts require the PHP GD extension. Enable extension=gd in C:\\xampp\\php\\php.ini and restart Apache or php artisan serve.');
        }

        $pricingService = new PricingService();

        $basePrice = $order->final_price ?? $order->estimated_price;

        $pricing = $pricingService->calculateCustomOrder($basePrice);

        $pdf = Pdf::loadView('user.receipt.customreceipt-pdf', [
            'order' => $order,
            'pricing' => $pricing,
        ]);

        return $pdf->download('custom-receipt-' . $order->id . '.pdf');
    }
}
