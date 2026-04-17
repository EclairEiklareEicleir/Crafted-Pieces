<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoreController::class, 'home']);
Route::get('/home', [StoreController::class, 'home'])->name('home');
Route::get('/shop', [StoreController::class, 'shop'])->name('shop');
Route::get('/shop/{slug}', [StoreController::class, 'product'])->name('product.show');
Route::get('/custom-order', [StoreController::class, 'customOrders'])->name('custom-order');
Route::get('/custom-orders', [StoreController::class, 'customOrders'])->name('custom-orders');
Route::get('/cart', [StoreController::class, 'cart'])->name('cart');
Route::get('/checkout', [StoreController::class, 'checkout'])->name('checkout');
Route::get('/my-orders', [StoreController::class, 'myOrders'])->name('my-orders');
Route::get('/about', [StoreController::class, 'about'])->name('about');

Route::prefix('admin')->name('admin.')->group(function () {
	Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
	Route::get('/products', [AdminController::class, 'products'])->name('products');
	Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
	Route::get('/products/{slug}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
	Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
	Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
	Route::get('/orders/{id}', [AdminController::class, 'orderDetail'])->name('orders.show');
	Route::get('/quotations', [AdminController::class, 'quotations'])->name('quotations');
	Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
	Route::get('/delivery', [AdminController::class, 'delivery'])->name('delivery');
	Route::get('/support', [AdminController::class, 'support'])->name('support');
	Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});
