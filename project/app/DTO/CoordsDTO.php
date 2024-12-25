<?php

namespace App\DTO;

readonly class CoordsDTO
{
    public function __construct(
        public string $latitude,
        public string $longitude
    ) {}
}
