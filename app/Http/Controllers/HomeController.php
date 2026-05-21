<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $preferredOrder = [
            'bouquets' => 0,
            'accessories' => 1,
            'plushies' => 2,
        ];

        $categories = Category::withCount('products')
            ->get()
            ->sortBy(function ($category) use ($preferredOrder) {
                return $preferredOrder[$category->slug] ?? 99;
            })
            ->values()
            ->map(function ($category) {
            return [
                'name' => $category->name,
                'slug' => $category->slug,
                'count' => $category->products_count,
                'image_url' => $category->image_url,
            ];
        });

        $featuredProducts = Product::latest()->take(8)->get();

        $steps = [
            [
                'title' => 'Browse',
                'desc' => 'Explore handmade crochet pieces available in our shop.',
            ],
            [
                'title' => 'Order',
                'desc' => 'Add items to cart and proceed to checkout.',
            ],
            [
                'title' => 'Crafted',
                'desc' => 'Each piece is carefully handmade with love.',
            ],
            [
                'title' => 'Delivered',
                'desc' => 'Your order is shipped safely to your door.',
            ],
        ];

        $testimonials = [
            [
                'name' => 'Customer A',
                'text' => 'Beautiful craftsmanship and fast delivery!',
                'rating' => 5,
            ],
            [
                'name' => 'Customer B',
                'text' => 'The custom order was exactly what I wanted.',
                'rating' => 5,
            ],
            [
                'name' => 'Customer C',
                'text' => 'High quality handmade items, highly recommended.',
                'rating' => 5,
            ],
        ];

        return view('user.home', compact(
            'categories',
            'featuredProducts',
            'steps',
            'testimonials'
        ));
    }
}