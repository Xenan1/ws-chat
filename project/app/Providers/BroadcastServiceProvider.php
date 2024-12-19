<?php

namespace App\Providers;


use App\Broadcasting\WebSocketBroadcaster;
use App\Logging\ChatLogger;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->make(BroadcastManager::class)->extend('custom', function ($app) {
            return new WebSocketBroadcaster(new ChatLogger());
        });
    }
}
