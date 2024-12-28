<?php

namespace App\DTO;

readonly class WeatherDTO
{
    public function __construct(
        public float $temperature,
        public float $windSpeed,
        public ?string $name,
    ) {}
}
