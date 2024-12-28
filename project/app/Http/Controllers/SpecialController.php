<?php

namespace App\Http\Controllers;

use App\Cache\CacheKeyStorage;
use App\Cache\CacheService;
use App\Http\Resources\WeatherResource;
use App\Http\Responses\CommonResponse;
use App\Services\SpecialService;
use Exception;

class SpecialController extends Controller
{
    public function __construct(
        protected SpecialService $service,
        protected CacheService $cache,
    ) {}

    public function getWeather(): WeatherResource|CommonResponse
    {
        $ip = request()->ip();

        return $ip
            ? $this->getWeatherResponse($ip)
            : new CommonResponse(false, 400);
    }

    public function getWeatherResponse(string $ip): WeatherResource|CommonResponse
    {
        try {
            $weather = $this->cache->remember(
                CacheKeyStorage::ipWeather($ip),
                function () use ($ip) {
                    return $this->service->getWeatherByIp($ip);
                },
                now()->addMinutes(5),
            );

            $response = new WeatherResource($weather);
        } catch (Exception) {
            $response = new CommonResponse(false, 503);
        }

        return $response;
    }
}
