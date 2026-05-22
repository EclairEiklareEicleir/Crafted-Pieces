<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Notification;

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

            $cartCount = $cart ? $cart->items()->sum('quantity') : 0;

            $unreadCount = 0;

            if (Auth::check()) {
                $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
            }

            $view->with([
                'cartCount' => $cartCount,
                'unreadCount' => $unreadCount,
            ]);
        });
    }
}
