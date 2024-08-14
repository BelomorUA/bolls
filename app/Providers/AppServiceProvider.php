<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Factories\Contracts\DeliveryFactoryInterface;
use App\Factories\DeliveryFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DeliveryFactoryInterface::class, function ($app) {
            return new DeliveryFactory();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
