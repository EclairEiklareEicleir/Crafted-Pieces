<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoreController::class, 'home']);
Route::get('/home', [StoreController::class, 'home'])->name('home');
Route::get('/shop', [StoreController::class, 'shop'])->name('shop');
Route::get('/shop/{slug}', [StoreController::class, 'product'])->name('product.show');
Route::get('/custom-order', [StoreController::class, 'customOrders'])->name('custom-order');
Route::get('/custom-orders', [StoreController::class, 'customOrders'])->name('custom-orders');
Route::get('/checkout', [StoreController::class, 'checkout'])->name('checkout');
Route::get('/about', [StoreController::class, 'about'])->name('about');

Route::middleware('guest')->group(function () {
	Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
	Route::post('/login', [AuthenticatedSessionController::class, 'store']);

	Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
	Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware(['auth', 'role:customer'])->group(function () {
	Route::get('/cart', [StoreController::class, 'cart'])->name('cart');
	Route::post('/cart/{slug}', [StoreController::class, 'addToCart'])->name('cart.add');
	Route::patch('/cart/items/{itemId}', [StoreController::class, 'updateCartItem'])->name('cart.items.update');
	Route::delete('/cart/items/{itemId}', [StoreController::class, 'removeCartItem'])->name('cart.items.remove');
	Route::post('/checkout', [StoreController::class, 'placeOrder'])->name('checkout.submit');
	Route::get('/my-orders', [StoreController::class, 'myOrders'])->name('my-orders');
	Route::get('/account', [StoreController::class, 'account'])->name('account');
	Route::patch('/account', [StoreController::class, 'updateAccount'])->name('account.update');
	Route::post('/custom-order', [StoreController::class, 'submitCustomOrder'])->name('custom-order.submit');
});

Route::middleware('auth')->post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:staff'])->group(function () {
	Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
	Route::get('/products', [AdminController::class, 'products'])->name('products');
	Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
	Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
	Route::get('/products/{slug}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
	Route::patch('/products/{slug}', [AdminController::class, 'updateProduct'])->name('products.update');
	Route::delete('/products/{slug}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
	Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
	Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
	Route::get('/orders/{id}', [AdminController::class, 'orderDetail'])->name('orders.show');
	Route::get('/quotations', [AdminController::class, 'quotations'])->name('quotations');
	Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
	Route::get('/delivery', [AdminController::class, 'delivery'])->name('delivery');
	Route::get('/support', [AdminController::class, 'support'])->name('support');
	Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});
