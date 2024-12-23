<?php

namespace App\Providers;

use App\Listeners\SendPostNotification;
use App\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Resources\Json\JsonResource;
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
        $this->app->bind(SendPostNotification::class, function (Application $app) {
            return new SendPostNotification($app->make(UserService::class), $app->make(config('notifications.service')));
        });

        JsonResource::withoutWrapping();
    }
}
