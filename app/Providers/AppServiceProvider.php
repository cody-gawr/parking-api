<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->bind(
            \App\Repositories\ParkingRepository::class,
            fn($app) => new \App\Repositories\ParkingRepository(new \App\Models\Parking)
        );

        $this->app->bind(
            \App\Repositories\NotificationRepository::class,
            fn($app) => new \App\Repositories\NotificationRepository()
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
