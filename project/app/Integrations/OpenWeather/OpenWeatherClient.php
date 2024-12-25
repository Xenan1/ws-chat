<?php

namespace App\Integrations\OpenWeather;

use App\DTO\CoordsDTO;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class OpenWeatherClient
{
    /**
     * @throws RequestException
     */
    public function getWeatherData(CoordsDTO $coords, array $exclude): array
    {
        $response = Http::get(config('integrations.open-weather.url'), [
            'lat' => $coords->latitude,
            'lon' => $coords->longitude,
            'appid' => config('integrations.open-weather.api-key'),
            'exclude' => implode(',', $exclude),
            'units' => 'metric',
        ]);

        $response->throw();

        return json_decode($response->body(), true);
    }
}
