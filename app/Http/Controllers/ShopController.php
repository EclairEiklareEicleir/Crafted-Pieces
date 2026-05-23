<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $activeCategory = $request->category;

        $categories = Category::all();

        $products = Product::query()
            ->with(['defaultVariant', 'variants', 'yarnColors'])
            ->when($activeCategory, function ($query) use ($activeCategory) {

                $query->whereHas('category', function ($q) use ($activeCategory) {
                    $q->where('slug', $activeCategory);
                });

            })
            ->latest()
            ->get();

        return view('user.shop', compact(
            'categories',
            'products',
            'activeCategory'
        ));
    }
}
