<?php

namespace App\Providers;

use App\Listeners\SendPostNotification;
use App\Parsing\AbstractParser;
use App\Parsing\HabrParser;
use App\Services\ConfigService;
use App\Services\UserService;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        AbstractParser::class => HabrParser::class,
    ];

    public $singletons = [
        ConfigService::class
    ];

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

        $this->app->singleton(ConfigService::class, function (Application $app) {
            return new ConfigService();
        });

        JsonResource::withoutWrapping();

        $this->scrambleConfiguration();
    }

    protected function scrambleConfiguration(): void
    {
        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });
    }
}
