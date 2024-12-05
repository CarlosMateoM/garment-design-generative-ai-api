<?php

namespace App\Providers;


use App\Services\GarmentDesignService;
use App\Services\OpenAI\ChatCompletionService;
use App\Services\OpenAI\DalleService;
use App\Services\OpenAI\OpenAIClient;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

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

        $this->app->singleton(ImageManager::class, function ($app) {
            return new ImageManager(new Driver());
        });

        $this->app->singleton(DalleService::class, function ($app) {
            return new DalleService($app->make(OpenAIClient::class));
        });

        $this->app->singleton(ChatCompletionService::class, function ($app) {
            return new ChatCompletionService($app->make(OpenAIClient::class));
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
