<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\CustomOrderRequest;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CheckoutController extends Controller
{
    private function getCart()
    {
        return Auth::check()
            ? Cart::firstOrCreate(['user_id' => Auth::id()])
            : Cart::firstOrCreate(['session_id' => session()->getId()]);
    }

    /*
    |--------------------------------------------------------------------------
    | CART CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $cart = $this->getCart();

        $cartItems = $cart
            ? $cart->items()->with('product')->get()
            : collect();

        $pricing = (new PricingService)->calculate($cartItems);

        return view('user.checkout', compact('cartItems', 'pricing'));
    }

    public function submit(Request $request)
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors(['checkout' => 'Your cart is empty.']);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'shipping_address' => 'required|string',
            'payment_method' => ['required', 'string', 'not_in:Bank Transfer'],
        ], [
            'payment_method.not_in' => 'Bank Transfer is no longer accepted. Please select another payment method.',
        ]);

        $pricing = (new PricingService)->calculate($cartItems);

        $order = Order::create([
            'user_id' => Auth::id(),

            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'shipping_address' => $validated['shipping_address'],
            'payment_method' => $validated['payment_method'],

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
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        $cart->items()->delete();

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
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

    public function downloadReceipt(Order $order)
    {
        $order->load('items.product');

        $pricing = (new PricingService)->calculateFromOrder($order);

        return Pdf::loadView('user.receipt.receipt-pdf', compact('order', 'pricing'))
            ->download('receipt-order-' . $order->id . '.pdf');
    }

    public function downloadCustomReceipt(CustomOrderRequest $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
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