<?php

namespace App\Providers;

use App\Services\DalleService;
use App\Services\GarmentDesignService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DalleService::class, function ($app){
            return new DalleService();
        });

        $this->app->singleton(GarmentDesignService::class, function ($app){
            return new GarmentDesignService($app->make(DalleService::class));
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
