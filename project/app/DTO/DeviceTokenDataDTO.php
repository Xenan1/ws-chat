<?php

namespace App\DTO;

readonly class DeviceTokenDataDTO
{
    public function __construct(
        public int $userId,
        public string $token,
    ) {}
}
