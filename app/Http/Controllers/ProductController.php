<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with(['category', 'variants' => function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        }, 'defaultVariant'])->where('slug', $slug)->firstOrFail();

        $product->syncMissingVariantsFromLegacyYarnColors();
        $product->load(['variants' => function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        }, 'defaultVariant']);

        return view('user.product', [
            'product' => $product
        ]);
    }
}
