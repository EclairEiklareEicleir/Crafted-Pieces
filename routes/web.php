<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomOrderPayMongoController;
use App\Http\Controllers\PayMongoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\AdminCustomOrderController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminAboutSectionController;
use App\Http\Controllers\AdminFaqController;
use App\Http\Controllers\AdminYarnColorController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| MAIN PAGES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/about', [StaticController::class, 'about'])->name('about');
Route::get('/privacy-policy', [StaticController::class, 'privacy'])->name('privacy.policy');
Route::get('/terms-of-service', [StaticController::class, 'terms'])->name('terms.service');

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

Route::post('/custom-order/{customOrder}/paymongo/checkout', [CustomOrderPayMongoController::class, 'checkout'])
    ->name('custom-order.paymongo.checkout');

Route::get('/custom-order/{customOrder}/paymongo/success', [CustomOrderPayMongoController::class, 'success'])
    ->name('custom-order.paymongo.success');

Route::get('/custom-order/{customOrder}/paymongo/cancel', [CustomOrderPayMongoController::class, 'cancel'])
    ->name('custom-order.paymongo.cancel');

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
Route::get('/checkout/paymongo/success/{order}', [PayMongoController::class, 'success'])->name('checkout.paymongo.success');
Route::get('/checkout/paymongo/cancel/{order}', [PayMongoController::class, 'cancel'])->name('checkout.paymongo.cancel');
Route::post('/checkout/paymongo/webhook', [PayMongoController::class, 'webhook'])->name('checkout.paymongo.webhook');

/*
|--------------------------------------------------------------------------
| ORDERS (USER)
|--------------------------------------------------------------------------
*/

Route::get('/track-order', [OrderController::class, 'trackForm'])->name('orders.track.form');
Route::post('/track-order', [OrderController::class, 'track'])->name('orders.track');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{order}/receipt', [OrderController::class, 'downloadReceipt'])->name('orders.receipt.download');
Route::get('/custom-order/{order}/receipt', [CheckoutController::class, 'downloadCustomReceipt'])->name('custom-order.receipt');

Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
    Route::patch('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');

    Route::get('/my-custom-orders', [CustomOrderController::class, 'index'])
        ->name('custom-order.index');

    /*
    |--------------------------------------------------------------------------
    | 💳 PAYMENT SYSTEM (RESTORED - SAFE ADDITION)
    |--------------------------------------------------------------------------
    */

    Route::get('/user/payment/{type}/{id}', [CheckoutController::class, 'payment'])
        ->name('user.payment');

    Route::post('/user/payment/{type}/{id}', [CheckoutController::class, 'processPayment'])
        ->name('user.payment.process');

    Route::post('/reviews', [ReviewController::class, 'store'])
        ->name('reviews.store');
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

    Route::get('/admin/about/edit', [AdminAboutSectionController::class, 'edit'])
        ->name('admin.about.edit');

    Route::put('/admin/about', [AdminAboutSectionController::class, 'update'])
        ->name('admin.about.update');

    Route::get('/admin/faq', [AdminFaqController::class, 'index'])
        ->name('admin.faq.index');

    Route::get('/admin/faq/create', [AdminFaqController::class, 'create'])
        ->name('admin.faq.create');

    Route::post('/admin/faq', [AdminFaqController::class, 'store'])
        ->name('admin.faq.store');

    Route::get('/admin/faq/{faq}/edit', [AdminFaqController::class, 'edit'])
        ->name('admin.faq.edit');

    Route::put('/admin/faq/{faq}', [AdminFaqController::class, 'update'])
        ->name('admin.faq.update');

    Route::delete('/admin/faq/{faq}', [AdminFaqController::class, 'destroy'])
        ->name('admin.faq.destroy');

    /*
    |--------------------------------------------------------------------------
    | ORDERS ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/orders/create', [AdminOrderController::class, 'create'])
        ->name('admin.orders.create');

    Route::get('/admin/orders', [AdminOrderController::class, 'index'])
        ->name('admin.orders.index');

    Route::get('/admin/orders/{order}', [AdminOrderController::class, 'show'])
        ->name('admin.orders.show');

    Route::post('/admin/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
        ->name('admin.orders.status');

    Route::delete('/admin/orders/{order}', [AdminOrderController::class, 'destroy'])
        ->name('admin.orders.destroy');

    Route::post('/admin/orders/bulk', [AdminOrderController::class, 'bulkAction'])
        ->name('admin.orders.bulk');

    Route::post('/admin/orders/manual', [AdminOrderController::class, 'store'])
        ->name('admin.orders.store');

    /*
    |--------------------------------------------------------------------------
    | 🧵 PRODUCTS ADMIN (NEW)
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/products', [AdminProductController::class, 'index'])
        ->name('admin.products.index');

    Route::get('/admin/products/create', [AdminProductController::class, 'create'])
        ->name('admin.products.create');

    Route::post('/admin/products', [AdminProductController::class, 'store'])
        ->name('admin.products.store');

    Route::get('/admin/products/{product}/edit', [AdminProductController::class, 'edit'])
        ->name('admin.products.edit');

    Route::put('/admin/products/{product}', [AdminProductController::class, 'update'])
        ->name('admin.products.update');

    Route::delete('/admin/products/{product}', [AdminProductController::class, 'destroy'])
        ->name('admin.products.destroy');

    Route::post('/admin/yarn-colors', [AdminYarnColorController::class, 'store'])
        ->name('admin.yarn-colors.store');

    Route::put('/admin/yarn-colors/{yarnColor}', [AdminYarnColorController::class, 'update'])
        ->name('admin.yarn-colors.update');

    Route::delete('/admin/yarn-colors/{yarnColor}', [AdminYarnColorController::class, 'destroy'])
        ->name('admin.yarn-colors.destroy');

    /*
    |--------------------------------------------------------------------------
    | 🧷 CATEGORY ADMIN (NEW - SAME CONTROLLER LOGIC)
    |--------------------------------------------------------------------------
    */
    Route::post('/admin/categories', [AdminCategoryController::class, 'store'])
        ->name('admin.categories.store');

    Route::put('/admin/categories/{category}', [AdminCategoryController::class, 'update'])
        ->name('admin.categories.update');

    Route::delete('/admin/categories/{category}', [AdminCategoryController::class, 'destroy'])
        ->name('admin.categories.destroy');

    /*
    |--------------------------------------------------------------------------
    | SETTINGS ADMIN
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/settings', [AdminSettingController::class, 'index'])
        ->name('admin.settings.index');

    Route::post('/admin/settings', [AdminSettingController::class, 'update'])
        ->name('admin.settings.update');
});

/*
|--------------------------------------------------------------------------
| AUTH SYSTEM
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| NOTIFICATIONS
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
});

/*
|--------------------------------------------------------------------------
| ERROR PAGES
|--------------------------------------------------------------------------
*/
Route::get('/test-404', function () {
    abort(404);
});

Route::get('/test-403', function () {
    abort(403);
});

Route::get('/test-500', function () {
    abort(500);
});


/*
|--------------------------------------------------------------------------
| TEST
|--------------------------------------------------------------------------
*/
Route::get('/test-error-ui', function () {
    return redirect()->route('home')
        ->withErrors(['test' => 'This is a TEST error for global alert UI']);
});

Route::get('/test-success-ui', function () {
    return redirect()->route('home')
        ->with('success', 'This is a TEST success message for global alert UI');
});

use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {

    Mail::raw('SMTP is working successfully.', function ($message) {

        $message->to('demoniczeno@gmail.com')
                ->subject('Crafted Pieces SMTP Test')
                ->from(
                    env('MAIL_FROM_ADDRESS'),
                    env('MAIL_FROM_NAME')
                );

    });

    return 'Mail sent successfully.';

});
