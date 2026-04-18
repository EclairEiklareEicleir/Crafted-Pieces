<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quotation;
use App\Support\ShowcaseData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    private function mapCategory(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'emoji' => $category->emoji,
            'count' => $category->products_count ?? 0,
        ];
    }

    private function mapProduct(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'image' => $product->image,
            'category_id' => $product->category_id,
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

    public function dashboard()
    {
        $totalOrders = Order::count();
        $pendingQuotations = Quotation::where('status', 'pending')->count();
        $revenue = Order::sum('total');

        $stats = [
            ['label' => 'Total Products', 'value' => Product::count()],
            ['label' => 'Total Orders', 'value' => $totalOrders],
            ['label' => 'Pending Quotations', 'value' => $pendingQuotations],
            ['label' => 'Revenue (Demo)', 'value' => 'PHP '.number_format($revenue)],
        ];

        $recentOrders = Order::with('items')
            ->latest()
            ->take(4)
            ->get()
            ->map(fn (Order $order) => $this->mapOrder($order))
            ->values()
            ->all();

        $quotations = Quotation::latest()
            ->get()
            ->map(fn (Quotation $quotation) => $this->mapQuotation($quotation))
            ->values()
            ->all();

        return view('admin.dashboard', [
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'quotations' => $quotations,
        ]);
    }

    public function products(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));
        $status = trim((string) $request->query('status', ''));

        $query = Product::with(['category', 'tags']);

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%');
            });
        }

        if ($category !== '') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        if (in_array($status, ['active', 'inactive', 'discontinued'], true)) {
            $query->where('status', $status);
        }

        $products = $query
            ->orderBy('name')
            ->get()
            ->map(fn (Product $product) => $this->mapProduct($product))
            ->values()
            ->all();

        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category) => $this->mapCategory($category))
            ->values()
            ->all();

        return view('admin.products', [
            'pageTitle' => 'Products',
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'q' => $search,
                'category' => $category,
                'status' => $status,
            ],
        ]);
    }

    public function createProduct()
    {
        $categories = Category::withCount('products')
            ->get()
            ->map(fn (Category $category) => $this->mapCategory($category))
            ->values()
            ->all();

        return view('admin.product-form', [
            'pageTitle' => 'Add Product',
            'mode' => 'create',
            'product' => null,
            'categories' => $categories,
        ]);
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('products', 'slug')],
            'short_description' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string', 'max:5000'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive', 'discontinued'])],
            'fulfillment' => ['required', Rule::in(['ready-stock', 'made-to-order'])],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        Product::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'image' => Storage::url($imagePath),
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'status' => $validated['status'],
            'fulfillment' => $validated['fulfillment'],
        ]);

        return redirect()->route('admin.products')->with('status', 'Product created successfully.');
    }

    public function editProduct(string $slug)
    {
        $product = Product::with(['category', 'tags'])->where('slug', $slug)->first();

        abort_if(! $product, 404);

        $categories = Category::withCount('products')
            ->get()
            ->map(fn (Category $category) => $this->mapCategory($category))
            ->values()
            ->all();

        $mappedProduct = $this->mapProduct($product);

        return view('admin.product-form', [
            'pageTitle' => 'Edit Product',
            'mode' => 'edit',
            'product' => $mappedProduct,
            'categories' => $categories,
        ]);
    }

    public function updateProduct(Request $request, string $slug): RedirectResponse
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('products', 'slug')->ignore($product->id)],
            'short_description' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string', 'max:5000'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive', 'discontinued'])],
            'fulfillment' => ['required', Rule::in(['ready-stock', 'made-to-order'])],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            if ($imagePath && str_starts_with($imagePath, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $imagePath));
            }

            $storedPath = $request->file('image')->store('products', 'public');
            $imagePath = Storage::url($storedPath);
        }

        $product->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'status' => $validated['status'],
            'fulfillment' => $validated['fulfillment'],
        ]);

        return redirect()->route('admin.products.edit', $product->slug)->with('status', 'Product updated successfully.');
    }

    public function destroyProduct(string $slug): RedirectResponse
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        if ($product->image && str_starts_with($product->image, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
        }

        $product->delete();

        return redirect()->route('admin.products')->with('status', 'Product deleted successfully.');
    }

    public function categories()
    {
        $categories = Category::withCount('products')
            ->get()
            ->map(fn (Category $category) => $this->mapCategory($category))
            ->values()
            ->all();

        return view('admin.categories', [
            'pageTitle' => 'Categories',
            'categories' => $categories,
        ]);
    }

    public function orders()
    {
        $orders = Order::with('items')
            ->latest()
            ->get()
            ->map(fn (Order $order) => $this->mapOrder($order))
            ->values()
            ->all();

        return view('admin.orders', [
            'pageTitle' => 'Orders',
            'orders' => $orders,
        ]);
    }

    public function orderDetail(string $id)
    {
        $order = Order::with('items')->where('order_number', $id)->first();

        abort_if(! $order, 404);

        $mappedOrder = $this->mapOrder($order);

        return view('admin.order-detail', [
            'pageTitle' => $id,
            'order' => $mappedOrder,
        ]);
    }

    public function quotations()
    {
        $quotations = Quotation::latest()
            ->get()
            ->map(fn (Quotation $quotation) => $this->mapQuotation($quotation))
            ->values()
            ->all();

        return view('admin.quotations', [
            'pageTitle' => 'Quotations',
            'quotations' => $quotations,
        ]);
    }

    public function payments()
    {
        $orders = Order::with('items')
            ->latest()
            ->get()
            ->map(fn (Order $order) => $this->mapOrder($order))
            ->values()
            ->all();

        return view('admin.payments', [
            'pageTitle' => 'Payments',
            'orders' => $orders,
        ]);
    }

    public function delivery()
    {
        $orders = Order::with('items')
            ->latest()
            ->get()
            ->map(fn (Order $order) => $this->mapOrder($order))
            ->values()
            ->all();

        return view('admin.delivery', [
            'pageTitle' => 'Delivery',
            'orders' => $orders,
        ]);
    }

    public function support()
    {
        return view('admin.support', [
            'pageTitle' => 'Support',
            'faq' => ShowcaseData::adminFaq(),
        ]);
    }

    public function settings()
    {
        return view('admin.settings', [
            'pageTitle' => 'Settings',
        ]);
    }
}
