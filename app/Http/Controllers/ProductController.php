<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with(['category', 'yarnColors', 'variants' => function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        }, 'defaultVariant'])->where('slug', $slug)->firstOrFail();

        return view('user.product', [
            'product' => $product
        ]);
    }
}
