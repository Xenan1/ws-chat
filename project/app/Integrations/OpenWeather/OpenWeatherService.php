<?php

namespace App\Integrations\OpenWeather;

use App\DTO\CoordsDTO;
use App\DTO\WeatherDTO;
use Illuminate\Http\Client\RequestException;

class OpenWeatherService
{
    public function __construct(protected OpenWeatherClient $client) {}

    /**
     * @throws RequestException
     */
    public function getWeather(CoordsDTO $coordsDTO): WeatherDTO
    {
        $weatherData = $this->client->getWeatherData($coordsDTO, ['minutely', 'hourly', 'daily', 'alerts']);
        $currentWeather = $weatherData['current'];
        $weatherName = $currentWeather['weather'][0]['main'] ?? null;

        return new WeatherDTO($currentWeather['temp'], $currentWeather['wind_speed'], $weatherName);
    }
}
