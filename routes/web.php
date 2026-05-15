<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\AdminCustomOrderController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminOrderController;

/*
|--------------------------------------------------------------------------
| MAIN PAGES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/about', [StaticController::class, 'about'])->name('about');

/*
|--------------------------------------------------------------------------
| CUSTOM ORDER SYSTEM (CLEAN)
|--------------------------------------------------------------------------
*/

// FORM ONLY (NO VARIABLES PASSED)
Route::get('/custom-order', function () {
    return view('user.custom-order');
})->name('custom-order');

// CREATE REQUEST
Route::post('/custom-order', [CustomOrderController::class, 'store'])
    ->name('custom-order.submit');

// TICKET / CHAT VIEW
Route::get('/custom-order/{customOrder}', [CustomOrderController::class, 'show'])
    ->name('custom-order.show');

// MESSAGE
Route::post('/custom-order/{customOrder}/message', [CustomOrderController::class, 'message'])
    ->name('custom-order.message');

// USER LIST (MY REQUESTS)
Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/my-custom-orders', [CustomOrderController::class, 'index'])
        ->name('custom-order.index');
});

/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/items/{id}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('/cart/items/{id}', [CartController::class, 'remove'])->name('cart.items.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'submit'])->name('checkout.submit');

Route::get('/order/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

/*
|--------------------------------------------------------------------------
| ORDERS (USER)
|--------------------------------------------------------------------------
*/

Route::get('/track-order', [OrderController::class, 'trackForm'])->name('orders.track.form');
Route::post('/track-order', [OrderController::class, 'track'])->name('orders.track');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

/*
|--------------------------------------------------------------------------
| USER AUTH
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');

    Route::get('/my-custom-orders', [CustomOrderController::class, 'index'])
        ->name('custom-order.index');

    /*
    |--------------------------------------------------------------------------
    | 💳 PAYMENT SYSTEM (RESTORED - SAFE ADDITION)
    |--------------------------------------------------------------------------
    |
    | NOTE: expanded middleware to ONLY 'auth'
    | so admin redirect does NOT get blocked later
    |
    */

    Route::middleware(['auth'])->group(function () {

        Route::get('/user/payment/{type}/{id}', [CheckoutController::class, 'payment'])
            ->name('user.payment');

        Route::post('/user/payment/{type}/{id}', [CheckoutController::class, 'processPayment'])
            ->name('user.payment.process');
    });
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:owner'])->group(function () {

    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/custom-requests', [AdminCustomOrderController::class, 'index'])
        ->name('admin.custom.index');

    Route::get('/custom-requests/{customOrder}', [AdminCustomOrderController::class, 'show'])
        ->name('admin.custom.show');

    Route::post('/custom-requests/{customOrder}/message', [AdminCustomOrderController::class, 'message'])
        ->name('admin.custom.message');

    Route::post('/custom-requests/{customOrder}/quote', [AdminCustomOrderController::class, 'quote'])
        ->name('admin.custom.quote');

    Route::post('/custom-order/{customOrder}/accept', [AdminCustomOrderController::class, 'accept'])
        ->name('admin.custom.accept');

    Route::post('/custom-order/{customOrder}/reject', [AdminCustomOrderController::class, 'reject'])
        ->name('admin.custom.reject');

    Route::get('/admin/orders', [AdminOrderController::class, 'index'])
        ->name('admin.orders.index');

    Route::get('/admin/orders/{order}', [AdminOrderController::class, 'show'])
        ->name('admin.orders.show');

    Route::post('/admin/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
        ->name('admin.orders.status');
});

/*
|--------------------------------------------------------------------------
| AUTH SYSTEM
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');