<?php

namespace App\Http\Resources;

use App\DTO\WeatherDTO;

class WeatherResource extends BaseJsonResource
{
    protected WeatherDTO $weatherDTO;

    public function __construct(WeatherDTO $resource)
    {
        $this->weatherDTO = $resource;
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        return [
            'temperature' => $this->weatherDTO->temperature,
            'wind_speed' => $this->weatherDTO->windSpeed,
            'name' => $this->weatherDTO->name,
        ];
    }
}
