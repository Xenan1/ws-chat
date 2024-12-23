<?php

namespace App\Cache;

readonly class CacheKey
{
    public function __construct(public string $key) {}
}
