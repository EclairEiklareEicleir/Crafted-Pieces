<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
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

        return Cart::firstOrCreate([
            'session_id' => session()->getId()
        ]);
    }

    public function index()
    {
        $cart = $this->getCart();

        $cartItems = $cart->items()
            ->with('product')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id' => $item->id,
                    'name' => $item->product?->name ?? 'Deleted Product',
                    'image' => $item->product?->image,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'slug' => $item->product?->slug,
                ];
            });

        return view('user.cart', compact('cartItems'));
    }

    public function add($slug)
    {
        $cart = $this->getCart();

        $product = Product::where('slug', $slug)->firstOrFail();

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }

        return back()->with('success', 'Added to cart');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $item = CartItem::findOrFail($id);

        $item->update([
            'quantity' => $request->quantity
        ]);

        return back()->with('success', 'Cart updated');
    }

    public function remove($id)
    {
        CartItem::findOrFail($id)->delete();

        return back()->with('success', 'Item removed');
    }
}