<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index', [
            'categories' => Category::latest()->get(),
            'products' => Product::with('category')->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => Category::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'product_type' => 'required|in:standard,custom',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        // =========================
        // IMAGE UPLOAD
        // =========================
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath, // STORE PATH ONLY
            'category_id' => $request->category_id,
            'product_type' => $request->product_type,
            'is_active' => true,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::all(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'product_type' => 'required|in:standard,custom',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (
            Product::where('slug', $slug)
                ->where('id', '!=', $product->id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter++;
        }

        // =========================
        // IMAGE UPDATE LOGIC
        // =========================
        $imagePath = $product->image;

        if ($request->hasFile('image')) {

            // delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // store new image
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'product_type' => $request->product_type,
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // delete image file too
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}