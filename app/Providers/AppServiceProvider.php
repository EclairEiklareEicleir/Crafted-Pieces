<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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
        View::composer('partials.store-nav', function ($view): void {
            $count = 0;
            $user = Auth::user();

            if ($user && $user->role === 'customer') {
                $count = (int) Cart::query()
                    ->where('user_id', $user->id)
                    ->withSum('items', 'quantity')
                    ->value('items_sum_quantity');
            }

            $view->with('cartItemCount', max(0, $count));
        });
    }
}
