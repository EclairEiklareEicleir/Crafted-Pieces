<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () { 
    return view('user.home'); 
})->name('home');

Route::get('/shop', function () { 
    return view('user.shop'); 
})->name('shop');

Route::get('/about', function () { 
    return view('user.about'); 
})->name('about');

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/account', fn () => view('user.account'))->name('account');
    Route::get('/orders', fn () => view('user.orders'))->name('my-orders');
    Route::get('/cart', fn () => view('user.cart'))->name('cart');
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/admin', fn () => view('admin.dashboard'));
});



Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



