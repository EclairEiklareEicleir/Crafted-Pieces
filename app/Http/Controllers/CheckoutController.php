<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\CustomOrderRequest;
use App\Services\PayMongoService;
use App\Services\PricingService;
use App\Support\SessionCart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class CheckoutController extends Controller
{
    private function getCart(Request $request, bool $create = true): ?Cart
    {
        return Auth::check() ? Cart::current($create) : null;
    }

    /*
    |--------------------------------------------------------------------------
    | CART CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $cartItems = $this->currentCartItems($request);

        $pricing = (new PricingService)->calculate($cartItems);

        return view('user.checkout', compact('cartItems', 'pricing'));
    }

    public function submit(Request $request)
    {
        $cart = $this->getCart($request, false);
        $cartItems = $this->currentCartItems($request);

        if ($cartItems->isEmpty()) {
            return back()->withErrors(['checkout' => 'Your cart is empty.']);
        }

        if ($message = $this->cartAvailabilityMessage($cartItems)) {
            return back()->withErrors(['checkout' => $message]);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'shipping_address' => 'required|string',
        ]);

        $pricing = (new PricingService)->calculate($cartItems);
        $paymentMethod = 'PayMongo';
        $guestSessionId = $request->session()->getId();

        $order = DB::transaction(function () use ($validated, $pricing, $cartItems, $paymentMethod, $guestSessionId) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_type' => 'online_order',
                'guest_session_id' => Auth::check() ? null : $guestSessionId,
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

            if (Auth::check()) {
                $cart?->items()->delete();
            } else {
                SessionCart::clear();
            }

            return redirect()->away($payMongoSession['checkout_url']);
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors(['checkout' => 'Unable to start PayMongo checkout right now. Please try again.']);
        }
    }

    public function success(Request $request, Order $order)
    {
        if (! $this->canAccessOrder($request, $order)) {
            abort(403);
        }

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

        if (! Auth::check() || $item->user_id !== Auth::id()) {
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

        if (! Auth::check() || $item->user_id !== Auth::id()) {
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
        if (! Auth::check() || $order->user_id !== Auth::id()) {
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

    private function currentCartItems(Request $request): Collection
    {
        if (! Auth::check()) {
            return SessionCart::items();
        }

        $cart = $this->getCart($request, false);

        return $cart
            ? $cart->items()->with(['product', 'productVariant', 'yarnColor'])->get()
            : collect();
    }

    private function cartAvailabilityMessage(Collection $cartItems): ?string
    {
        foreach ($cartItems as $item) {
            if ($message = $this->productAvailabilityMessage($item->product, (int) $item->quantity, $item->productVariant)) {
                return $message;
            }
        }

        return null;
    }

    private function productAvailabilityMessage(?\App\Models\Product $product, int $quantity, ?\App\Models\ProductVariant $variant = null): ?string
    {
        if (! $product) {
            return 'One of the products in your cart is no longer available.';
        }

        if (! $product->is_active) {
            return $product->name . ' is not available right now.';
        }

        $availableStock = $variant && $variant->stock !== null
            ? (int) $variant->stock
            : (int) $product->stock;

        if ($availableStock < 1) {
            return $product->name . ' is out of stock.';
        }

        if ($quantity > $availableStock) {
            return 'Only ' . $availableStock . ' item(s) are available for ' . $product->name . '.';
        }

        return null;
    }
}
