<?php

namespace App\Providers;

use App\Services\GarmentDesignService;
use App\Services\OpenAI\ChatCompletionService;
use App\Services\OpenAI\DalleService;
use App\Services\OpenAI\OpenAIClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OpenAIClient::class, function ($app) {
            return new OpenAIClient();
        });

        $this->app->singleton(DalleService::class, function ($app) {
            return new DalleService($app->make(OpenAIClient::class));
        });

        $this->app->singleton(ChatCompletionService::class, function ($app) {
            return new ChatCompletionService($app->make(OpenAIClient::class));
        });

        $this->app->singleton(GarmentDesignService::class, function ($app) {
            return new GarmentDesignService(
                $app->make(DalleService::class),
                $app->make(ChatCompletionService::class)
            );
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
