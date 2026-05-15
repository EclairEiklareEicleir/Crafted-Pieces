<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\CustomOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CART HANDLING
    |--------------------------------------------------------------------------
    */

    private function getCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate([
                'user_id' => Auth::id()
            ]);
        }

        return Cart::firstOrCreate([
            'session_id' => session()->getId()
        ]);
    }

    public function index()
    {
        $cart = $this->getCart();

        $cartItems = $cart
            ? $cart->items()->with('product')->get()
            : collect();

        return view('user.checkout', compact('cartItems'));
    }

    public function submit(Request $request)
    {
        $cart = $this->getCart();

        if (! $cart || $cart->items()->count() === 0) {
            return back()->withErrors([
                'checkout' => 'Your cart is empty.'
            ]);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cartItems = $cart->items()->with('product')->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $order = Order::create([
            'user_id' => Auth::id(),
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'shipping_address' => $validated['shipping_address'],
            'payment_method' => $validated['payment_method'],
            'total_amount' => $total,
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
    | UNIVERSAL PAYMENT SYSTEM (CUSTOM + ORDERS)
    |--------------------------------------------------------------------------
    */

    public function payment($type, $id)
    {
        $item = $this->resolvePaymentItem($type, $id);

        /*
        |--------------------------------------------------------------------------
        | AUTO EXPIRE UNPAID CUSTOM ORDERS
        |--------------------------------------------------------------------------
        */
        if (
            $type === 'custom-order' &&
            $item->paymentIsExpired()
        ) {

            $item->update([
                'status' => CustomOrderRequest::STATUS_REJECTED
            ]);

            return redirect()
                ->route('custom-order.show', $item)
                ->withErrors([
                    'payment' => 'Payment deadline has expired.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SECURITY CHECK
        |--------------------------------------------------------------------------
        */
        if (Auth::check()) {

            if (
                $type === 'custom-order' &&
                $item->user_id !== Auth::id()
            ) {
                abort(403, 'Unauthorized payment access.');
            }

            if (
                $type === 'order' &&
                $item->user_id !== Auth::id()
            ) {
                abort(403, 'Unauthorized payment access.');
            }
        }

        $amount = $this->resolveAmount($item, $type);

        $breakdown = $this->buildPaymentBreakdown($amount);

        return view('user.payment', [
            'type' => $type,
            'item' => $item,
            'breakdown' => $breakdown
        ]);
    }

    public function processPayment(Request $request, $type, $id)
    {
        $item = $this->resolvePaymentItem($type, $id);

        /*
        |--------------------------------------------------------------------------
        | AUTO EXPIRE BEFORE PAYMENT PROCESS
        |--------------------------------------------------------------------------
        */
        if (
            $type === 'custom-order' &&
            $item->paymentIsExpired()
        ) {

            $item->update([
                'status' => CustomOrderRequest::STATUS_REJECTED
            ]);

            return redirect()
                ->route('custom-order.show', $item)
                ->withErrors([
                    'payment' => 'Payment deadline expired.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SECURITY CHECK
        |--------------------------------------------------------------------------
        */
        if (
            $type === 'custom-order' &&
            $item->user_id !== Auth::id()
        ) {
            abort(403);
        }

        if (
            $type === 'order' &&
            $item->user_id !== Auth::id()
        ) {
            abort(403);
        }

        switch ($type) {

            case 'custom-order':

                $item->update([
                    'status' => CustomOrderRequest::STATUS_PAID,
                    'paid_at' => now(),
                ]);

                return redirect()
                    ->route('custom-order.show', $item)
                    ->with('success', 'Custom order payment successful.');

            case 'order':

                $item->update([
                    'status' => 'paid'
                ]);

                return redirect()
                    ->route('checkout.success', $item)
                    ->with('success', 'Order payment successful.');

            default:
                abort(404);
        }
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
    | AMOUNT RESOLVER
    |--------------------------------------------------------------------------
    */

    private function resolveAmount($item, $type)
    {
        return match ($type) {

            'custom-order' => $item->final_price ?? $item->estimated_price,

            'order' => $item->total_amount,

            default => 0,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT BREAKDOWN ENGINE
    |--------------------------------------------------------------------------
    */

    private function buildPaymentBreakdown($amount)
    {
        $platformFeeRate = 0.05; // 5%
        $depositRate = 0.50;     // 50%

        $platformFee = $amount * $platformFeeRate;
        $total = $amount + $platformFee;

        $deposit = $total * $depositRate;
        $balance = $total - $deposit;

        return [
            'base_amount' => round($amount, 2),
            'platform_fee' => round($platformFee, 2),
            'total_amount' => round($total, 2),
            'deposit' => round($deposit, 2),
            'balance' => round($balance, 2),
        ];
    }
}