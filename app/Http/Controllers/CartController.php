<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\YarnColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate([
                'user_id' => Auth::id()
            ]);
        }

        return null;
    }

    public function index()
    {
        $cart = $this->getCart();

        $cartItems = $cart
            ? $cart->items()->with(['product', 'productVariant', 'yarnColor'])->latest()->get()
            : collect();

        return view('user.cart', compact('cartItems'));
    }

    public function add(Request $request, $slug)
    {
        if (! Auth::check()) {
            return back()
                ->withErrors(['auth' => 'Please log in to add items to your cart.'])
                ->with('auth_form', 'login');
        }

        $request->validate([
            'yarn_color_id' => 'required|integer|exists:yarn_colors,id',
            'product_variant_id' => 'nullable|integer|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1|max:99',
        ]);

        $cart = $this->getCart();
        if (! $cart) {
            return back()
                ->withErrors(['auth' => 'Please log in to add items to your cart.'])
                ->with('auth_form', 'login');
        }

        $quantity = (int) $request->input('quantity', 1);

        $product = Product::with(['variants' => function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        }, 'defaultVariant', 'yarnColors'])->where('slug', $slug)->firstOrFail();

        $availableYarnColors = YarnColor::activeOptionsForProduct($product);
        $yarnColor = $availableYarnColors->firstWhere('id', (int) $request->yarn_color_id);

        if (! $yarnColor) {
            return back()->withErrors(['yarn_color_id' => 'Please choose an available yarn color for this product.']);
        }

        $variant = $product->variantForYarnColor($yarnColor);

        if ($request->filled('product_variant_id')) {
            $requestedVariant = $product->variants->firstWhere('id', (int) $request->product_variant_id);

            if ($requestedVariant && $product->variantMatchesYarnColor($requestedVariant, $yarnColor)) {
                $variant = $requestedVariant;
            }
        }

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->where('yarn_color_id', $yarnColor->id)
            ->first();

        if ($item) {
            $item->increment('quantity', $quantity);

            if ($variant && $item->product_variant_id !== $variant->id) {
                $item->update([
                    'product_variant_id' => $variant->id,
                    'variant_image_path' => $variant->image_path,
                ]);
            }
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'yarn_color_id' => $yarnColor->id,
                'variant_name' => $yarnColor->name,
                'variant_hex_color' => $yarnColor->hex_color,
                'variant_image_path' => $variant?->image_path ?? $product->image,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        return back()->with('success', 'Added to cart');
    }

    public function update(Request $request, $id)
    {
        if (! Auth::check()) {
            return back()
                ->withErrors(['auth' => 'Please log in to update your cart.'])
                ->with('auth_form', 'login');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $item = CartItem::where('id', $id)
            ->whereHas('cart', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        $item->update([
            'quantity' => $request->quantity
        ]);

        return back()->with('success', 'Cart updated');
    }

    public function remove($id)
    {
        if (! Auth::check()) {
            return back()
                ->withErrors(['auth' => 'Please log in to update your cart.'])
                ->with('auth_form', 'login');
        }

        CartItem::where('id', $id)
            ->whereHas('cart', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail()
            ->delete();

        return back()->with('success', 'Item removed');
    }
}
