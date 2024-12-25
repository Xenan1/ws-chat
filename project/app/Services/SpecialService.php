<?php

namespace App\Services;

use App\DTO\WeatherDTO;
use App\Integrations\IpApi\IpApiService;
use App\Integrations\OpenWeather\OpenWeatherService;
use Illuminate\Http\Client\RequestException;

class SpecialService
{
    public function __construct(
        protected IpApiService $ipApiService,
        protected OpenWeatherService $openWeatherService,
    ) {}

    /**
     * @throws RequestException
     */
    public function getWeatherByIp(string $ip): WeatherDTO
    {
        $coords = $this->ipApiService->getIpCoords($ip);

        return $this->openWeatherService->getWeather($coords);
    }
}
