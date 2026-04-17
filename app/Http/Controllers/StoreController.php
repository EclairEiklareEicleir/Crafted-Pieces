<?php

namespace App\Http\Controllers;

use App\Support\ShowcaseData;

class StoreController extends Controller
{
    public function home()
    {
        return view('users.home', [
            'pageTitle' => 'the_crafted_pieces',
            'categories' => ShowcaseData::categories(),
            'featuredProducts' => ShowcaseData::featuredProducts(),
            'newArrivals' => ShowcaseData::newArrivals(),
            'steps' => ShowcaseData::steps(),
            'testimonials' => ShowcaseData::testimonials(),
        ]);
    }

    public function shop()
    {
        $category = request('category');

        return view('users.shop', [
            'pageTitle' => 'Shop',
            'categories' => ShowcaseData::categories(),
            'products' => ShowcaseData::productsByCategory($category),
            'activeCategory' => $category,
        ]);
    }

    public function product(string $slug)
    {
        $product = ShowcaseData::findProduct($slug);

        abort_if(! $product, 404);

        return view('users.product', [
            'pageTitle' => $product['name'],
            'product' => $product,
        ]);
    }

    public function cart()
    {
        return view('users.cart', [
            'pageTitle' => 'Cart',
            'cartItems' => ShowcaseData::cartItems(),
        ]);
    }

    public function checkout()
    {
        return view('users.checkout', [
            'pageTitle' => 'Checkout',
            'cartItems' => ShowcaseData::cartItems(),
        ]);
    }

    public function customOrders()
    {
        return view('users.custom-order', [
            'pageTitle' => 'Custom Orders',
        ]);
    }

    public function myOrders()
    {
        return view('users.my-orders', [
            'pageTitle' => 'My Orders',
            'orders' => ShowcaseData::orders(),
            'quotations' => ShowcaseData::quotations(),
        ]);
    }

    public function about()
    {
        return view('users.about', [
            'pageTitle' => 'About',
            'faq' => ShowcaseData::faq(),
        ]);
    }
}
