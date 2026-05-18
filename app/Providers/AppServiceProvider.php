<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.navbar', function ($view) {
            $cart = Auth::check()
                ? Cart::where('user_id', Auth::id())->first()
                : Cart::where('session_id', session()->getId())->first();

            $view->with('cartCount', $cart ? $cart->items()->sum('quantity') : 0);
        });
    }
}
