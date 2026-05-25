<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\SessionCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getCart(bool $create = true)
    {
        return Cart::current($create);
    }

    public function index()
    {
        if (Auth::check()) {
            $cart = $this->getCart(false);

            $cartItems = $cart
                ? $cart->items()->with(['product', 'productVariant', 'yarnColor'])->latest()->get()
                : collect();
        } else {
            $cartItems = SessionCart::items();
        }

        return view('user.cart', compact('cartItems'));
    }

    public function add(Request $request, $slug)
    {
        $request->validate([
            'product_variant_id' => 'required|integer|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1|max:99',
        ]);

        $quantity = (int) $request->input('quantity', 1);

        $product = Product::with(['variants' => function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        }, 'defaultVariant'])->where('slug', $slug)->firstOrFail();

        $product->syncMissingVariantsFromLegacyYarnColors();
        $product->load(['variants' => function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        }, 'defaultVariant']);

        if ($message = $this->productAvailabilityMessage($product, $quantity)) {
            return back()->withErrors(['product' => $message]);
        }

        $variant = $product->variants->firstWhere('id', (int) $request->product_variant_id);

        if (! $variant) {
            return back()->withErrors(['product_variant_id' => 'Please choose an available variant for this product.']);
        }

        if (($variant->status ?? 'inactive') !== 'active' || (int) ($variant->stock ?? 0) < 1) {
            return back()->withErrors(['product_variant_id' => 'Please choose an available variant for this product.']);
        }

        if (! Auth::check()) {
            $newQuantity = SessionCart::quantityFor($product, $variant) + $quantity;

            if ($message = $this->productAvailabilityMessage($product, $newQuantity, $variant)) {
                return back()->withErrors(['quantity' => $message]);
            }

            SessionCart::add($product, $variant, null, $quantity);

            return back()->with('success', 'Added to cart');
        }

        $cart = $this->getCart();
        if (! $cart) {
            return back()->withErrors(['cart' => 'Unable to access your cart. Please refresh and try again.']);
        }

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variant->id)
            ->first();

        $newQuantity = (int) ($item?->quantity ?? 0) + $quantity;

        if ($message = $this->productAvailabilityMessage($product, $newQuantity, $variant)) {
            return back()->withErrors(['quantity' => $message]);
        }

        if ($item) {
            $item->increment('quantity', $quantity);

            if ($item->product_variant_id !== $variant->id) {
                $item->update([
                    'product_variant_id' => $variant->id,
                    'yarn_color_id' => null,
                    'variant_name' => $variant->name ?: $variant->yarn_color,
                    'variant_hex_color' => $variant->hex_color,
                    'variant_image_path' => $variant->image_path,
                ]);
            }
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'yarn_color_id' => null,
                'variant_name' => $variant->name ?: $variant->yarn_color,
                'variant_hex_color' => $variant->hex_color,
                'variant_image_path' => $variant->image_path ?? $product->image,
                'quantity' => $quantity,
                'price' => $variant->price ?? $product->price,
            ]);
        }

        return back()->with('success', 'Added to cart');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $quantity = (int) $request->quantity;

        if (! Auth::check()) {
            $item = SessionCart::item((string) $id);

            if (! $item) {
                return back()->withErrors(['cart' => 'That cart item could not be found.']);
            }

            if ($message = $this->productAvailabilityMessage($item->product, $quantity, $item->productVariant)) {
                return back()->withErrors(['quantity' => $message]);
            }

            SessionCart::update((string) $id, $quantity);

            return back()->with('success', 'Cart updated');
        }

        $cart = $this->getCart(false);

        if (! $cart) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $item = CartItem::with(['product', 'productVariant', 'yarnColor'])
            ->where('id', $id)
            ->whereHas('cart', function ($query) use ($cart) {
                $query->where('id', $cart->id);
            })
            ->first();

        if (! $item) {
            return back()->withErrors(['cart' => 'That cart item could not be found.']);
        }

        if ($message = $this->productAvailabilityMessage($item->product, $quantity, $item->productVariant)) {
            return back()->withErrors(['quantity' => $message]);
        }

        $item->update([
            'quantity' => $quantity
        ]);

        return back()->with('success', 'Cart updated');
    }

    public function remove($id)
    {
        if (! Auth::check()) {
            if (! SessionCart::remove((string) $id)) {
                return back()->withErrors(['cart' => 'That cart item could not be found.']);
            }

            return back()->with('success', 'Item removed');
        }

        $cart = $this->getCart(false);

        if (! $cart) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $item = CartItem::where('id', $id)
            ->whereHas('cart', function ($query) use ($cart) {
                $query->where('id', $cart->id);
            })
            ->first();

        if (! $item) {
            return back()->withErrors(['cart' => 'That cart item could not be found.']);
        }

        $item->delete();

        return back()->with('success', 'Item removed');
    }

    private function productAvailabilityMessage(?Product $product, int $quantity, ?ProductVariant $variant = null): ?string
    {
        if (! $product) {
            return 'This product is no longer available.';
        }

        if (! $product->is_active) {
            return 'This product is not available right now.';
        }

        $availableStock = $variant && $variant->stock !== null
            ? (int) $variant->stock
            : (int) $product->stock;

        if ($availableStock < 1) {
            return 'This product is out of stock.';
        }

        if ($quantity > $availableStock) {
            return 'Only ' . $availableStock . ' item(s) are available for this product.';
        }

        return null;
    }
}
