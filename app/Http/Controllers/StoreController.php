<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quotation;
use App\Support\ShowcaseData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    private const CHECKOUT_PAYMENT_METHODS = ['GCash', 'Maya', 'Bank Transfer'];

    private function currentCart(Request $request): Cart
    {
        return Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);
    }

    private function mapCartItem($cartItem): array
    {
        return [
            'id' => $cartItem->id,
            'name' => $cartItem->product_name,
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->price,
        ];
    }

    private function mapCategory(Category $category): array
    {
        return [
            'name' => $category->name,
            'slug' => $category->slug,
            'emoji' => $category->emoji,
            'count' => $category->products_count ?? 0,
        ];
    }

    private function mapProduct(Product $product): array
    {
        return [
            'name' => $product->name,
            'slug' => $product->slug,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'image' => $product->image,
            'category' => $product->category?->name,
            'price' => $product->price,
            'tags' => $product->tags->pluck('name')->values()->all(),
            'stock' => $product->stock,
            'status' => $product->status,
            'fulfillment' => $product->fulfillment,
        ];
    }

    private function mapOrder(Order $order): array
    {
        return [
            'id' => $order->order_number,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'items' => $order->items->map(fn ($item) => [
                'product_name' => $item->product_name,
                'variant' => $item->variant,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ])->values()->all(),
            'total' => $order->total,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'shipping_address' => $order->shipping_address,
            'created_at' => optional($order->created_at)->format('Y-m-d'),
            'updated_at' => optional($order->updated_at)->format('Y-m-d'),
        ];
    }

    private function mapQuotation(Quotation $quotation): array
    {
        return [
            'id' => $quotation->quotation_number,
            'customer_name' => $quotation->customer_name,
            'item_type' => $quotation->item_type,
            'design_theme' => $quotation->design_theme,
            'status' => $quotation->status,
            'quoted_price' => $quotation->quoted_price,
        ];
    }

    public function home()
    {
        $categories = Category::withCount('products')
            ->get()
            ->map(fn (Category $category) => $this->mapCategory($category))
            ->values()
            ->all();

        $featuredProducts = Product::with(['category', 'tags'])
            ->whereHas('tags', fn ($q) => $q->where('name', 'Bestseller'))
            ->get()
            ->map(fn (Product $product) => $this->mapProduct($product))
            ->values()
            ->all();

        $newArrivals = Product::with(['category', 'tags'])
            ->whereHas('tags', fn ($q) => $q->where('name', 'New Arrival'))
            ->get()
            ->map(fn (Product $product) => $this->mapProduct($product))
            ->values()
            ->all();

        return view('users.home', [
            'pageTitle' => 'the_crafted_pieces',
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'newArrivals' => $newArrivals,
            'steps' => ShowcaseData::steps(),
            'testimonials' => ShowcaseData::testimonials(),
        ]);
    }

    public function shop()
    {
        $category = request('category');

        $query = Product::with(['category', 'tags']);

        if ($category && $category !== 'all') {
            $query = $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        $categories = Category::withCount('products')
            ->get()
            ->map(fn (Category $item) => $this->mapCategory($item))
            ->values()
            ->all();

        $products = $query->get()
            ->map(fn (Product $product) => $this->mapProduct($product))
            ->values()
            ->all();

        return view('users.shop', [
            'pageTitle' => 'Shop',
            'categories' => $categories,
            'products' => $products,
            'activeCategory' => $category,
        ]);
    }

    public function product(string $slug)
    {
        $product = Product::with(['category', 'tags'])->where('slug', $slug)->first();

        abort_if(! $product, 404);

        $mappedProduct = $this->mapProduct($product);

        return view('users.product', [
            'pageTitle' => $mappedProduct['name'],
            'product' => $mappedProduct,
        ]);
    }

    public function cart()
    {
        $cartItems = $this->currentCart(request())
            ->items()
            ->orderBy('id')
            ->get()
            ->map(fn ($item) => $this->mapCartItem($item))
            ->values()
            ->all();

        return view('users.cart', [
            'pageTitle' => 'Cart',
            'cartItems' => $cartItems,
        ]);
    }

    public function checkout()
    {
        if (! request()->user()) {
            return view('users.checkout', [
                'pageTitle' => 'Checkout',
                'cartItems' => [],
            ]);
        }

        $cartItems = $this->currentCart(request())
            ->items()
            ->orderBy('id')
            ->get()
            ->map(fn ($item) => $this->mapCartItem($item))
            ->values()
            ->all();

        if (empty($cartItems)) {
            return redirect()->route('cart')->withErrors([
                'checkout' => 'Your cart is empty. Add items before proceeding to checkout.',
            ]);
        }

        return view('users.checkout', [
            'pageTitle' => 'Checkout',
            'cartItems' => $cartItems,
        ]);
    }

    public function addToCart(Request $request, string $slug): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::where('slug', $slug)->firstOrFail();
        $quantity = (int) ($validated['quantity'] ?? 1);

        $cart = $this->currentCart($request);
        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantity,
                'price' => $product->price,
                'product_name' => $product->name,
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        return redirect()->route('cart')->with('status', $product->name.' added to cart.');
    }

    public function updateCartItem(Request $request, int $itemId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $this->currentCart($request);
        $item = $cart->items()->whereKey($itemId)->firstOrFail();

        $item->update([
            'quantity' => (int) $validated['quantity'],
        ]);

        return redirect()->route('cart')->with('status', 'Cart item quantity updated.');
    }

    public function removeCartItem(Request $request, int $itemId): RedirectResponse
    {
        $cart = $this->currentCart($request);
        $item = $cart->items()->whereKey($itemId)->firstOrFail();

        $item->delete();

        return redirect()->route('cart')->with('status', 'Item removed from cart.');
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $cart = $this->currentCart($request);
        $cartItems = $cart->items()->orderBy('id')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->withErrors([
                'checkout' => 'Cart is empty. Add items before placing an order.',
            ]);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:'.implode(',', self::CHECKOUT_PAYMENT_METHODS)],
        ]);

        $order = DB::transaction(function () use ($request, $validated, $cartItems, $cart) {
            $total = (int) $cartItems->sum(fn ($item) => ((int) $item->quantity) * ((int) $item->price));

            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_number' => $this->nextOrderNumber(),
                'customer_name' => $validated['full_name'],
                'customer_email' => $validated['email'],
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['shipping_address'],
            ]);

            $order->items()->createMany(
                $cartItems->map(fn ($item) => [
                    'product_name' => $item->product_name,
                    'variant' => null,
                    'quantity' => (int) $item->quantity,
                    'price' => (int) $item->price,
                ])->values()->all()
            );

            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('my-orders')->with('status', 'Order '.$order->order_number.' has been placed successfully.');
    }

    public function customOrders()
    {
        return view('users.custom-order', [
            'pageTitle' => 'Custom Orders',
        ]);
    }

    public function submitCustomOrder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'item_type' => ['required', 'string', 'max:255'],
            'design_theme' => ['required', 'string', 'max:255'],
            'preferred_size' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
        ]);

        $quotation = Quotation::create([
            'user_id' => $request->user()->id,
            'quotation_number' => $this->nextQuotationNumber(),
            'customer_name' => $validated['name'],
            'customer_email' => $validated['email'],
            'item_type' => $validated['item_type'],
            'design_theme' => $validated['design_theme'],
            'preferred_size' => $validated['preferred_size'] ?? null,
            'description' => $validated['description'],
            'status' => 'pending',
            'quoted_price' => null,
        ]);

        return redirect()->route('my-orders')->with('status', 'Quotation request '.$quotation->quotation_number.' has been submitted.');
    }

    public function myOrders()
    {
        $userId = request()->user()->id;

        $orders = Order::with('items')
            ->ownedBy($userId)
            ->latest()
            ->get()
            ->map(fn (Order $order) => $this->mapOrder($order))
            ->values()
            ->all();

        $quotations = Quotation::ownedBy($userId)
            ->latest()
            ->get()
            ->map(fn (Quotation $quotation) => $this->mapQuotation($quotation))
            ->values()
            ->all();

        return view('users.my-orders', [
            'pageTitle' => 'My Orders',
            'orders' => $orders,
            'quotations' => $quotations,
        ]);
    }

    public function account()
    {
        return view('users.account', [
            'pageTitle' => 'Account',
            'user' => request()->user(),
        ]);
    }

    public function updateAccount(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return redirect()->route('account')->with('status', 'Account details updated successfully.');
    }

    public function about()
    {
        return view('users.about', [
            'pageTitle' => 'About',
            'faq' => ShowcaseData::faq(),
        ]);
    }

    private function nextOrderNumber(): string
    {
        $latest = Order::query()
            ->select('order_number')
            ->orderByDesc('id')
            ->value('order_number');

        if (! $latest || ! preg_match('/^ORD-(\d+)$/', $latest, $matches)) {
            return 'ORD-001';
        }

        return 'ORD-'.str_pad((string) ((int) $matches[1] + 1), 3, '0', STR_PAD_LEFT);
    }

    private function nextQuotationNumber(): string
    {
        $latest = Quotation::query()
            ->select('quotation_number')
            ->orderByDesc('id')
            ->value('quotation_number');

        if (! $latest || ! preg_match('/^QR-(\d+)$/', $latest, $matches)) {
            return 'QR-001';
        }

        return 'QR-'.str_pad((string) ((int) $matches[1] + 1), 3, '0', STR_PAD_LEFT);
    }
}
