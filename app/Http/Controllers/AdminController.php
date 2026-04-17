<?php

namespace App\Http\Controllers;

use App\Support\ShowcaseData;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'pageTitle' => 'Dashboard',
            'stats' => ShowcaseData::stats(),
            'recentOrders' => array_slice(ShowcaseData::orders(), 0, 4),
            'quotations' => ShowcaseData::quotations(),
        ]);
    }

    public function products()
    {
        return view('admin.products', [
            'pageTitle' => 'Products',
            'products' => ShowcaseData::products(),
        ]);
    }

    public function createProduct()
    {
        return view('admin.product-form', [
            'pageTitle' => 'Add Product',
            'mode' => 'create',
            'product' => null,
            'categories' => ShowcaseData::categories(),
        ]);
    }

    public function editProduct(string $slug)
    {
        $product = ShowcaseData::findProduct($slug);

        abort_if(! $product, 404);

        return view('admin.product-form', [
            'pageTitle' => 'Edit Product',
            'mode' => 'edit',
            'product' => $product,
            'categories' => ShowcaseData::categories(),
        ]);
    }

    public function categories()
    {
        return view('admin.categories', [
            'pageTitle' => 'Categories',
            'categories' => ShowcaseData::categories(),
        ]);
    }

    public function orders()
    {
        return view('admin.orders', [
            'pageTitle' => 'Orders',
            'orders' => ShowcaseData::orders(),
        ]);
    }

    public function orderDetail(string $id)
    {
        $order = collect(ShowcaseData::orders())->firstWhere('id', $id);

        abort_if(! $order, 404);

        return view('admin.order-detail', [
            'pageTitle' => $id,
            'order' => $order,
        ]);
    }

    public function quotations()
    {
        return view('admin.quotations', [
            'pageTitle' => 'Quotations',
            'quotations' => ShowcaseData::quotations(),
        ]);
    }

    public function payments()
    {
        return view('admin.payments', [
            'pageTitle' => 'Payments',
            'orders' => ShowcaseData::orders(),
        ]);
    }

    public function delivery()
    {
        return view('admin.delivery', [
            'pageTitle' => 'Delivery',
            'orders' => ShowcaseData::orders(),
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
