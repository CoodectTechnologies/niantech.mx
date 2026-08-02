<?php

namespace App\Providers;

use App\Models\Subscription;
use App\Services\Cart\CustomCart;
use App\Support\NgrokProcessBuilder;
use Gloudemans\Shoppingcart\Cart;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use JnJairo\Laravel\Ngrok\NgrokProcessBuilder as VendorNgrokProcessBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Cart::class, function ($app) {
            return new CustomCart($app['session'], $app['events']);
        });
        $this->app->bind(VendorNgrokProcessBuilder::class, function ($app) {
            return new NgrokProcessBuilder($app->basePath());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::useSubscriptionModel(Subscription::class);
    }
}
