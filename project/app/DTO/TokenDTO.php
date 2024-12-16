<?php

namespace App\DTO;

readonly class TokenDTO
{
    public function __construct(
        public string $accessToken,
        public string $type,
        public int $expireTime,
    ) {}
}
