<?php

namespace App\Integrations\IpApi\DTO;

readonly class IpCoordsDTO
{
    public function __construct(
        public string $latitude,
        public string $longitude
    ) {}
}
