<?php

namespace App\Cache;

use App\Logging\CacheLogger;
use App\Logging\Enum\LogLevels;
use Closure;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function __construct(protected CacheLogger $logger) {}

    public function put(CacheKey $key, mixed $value): void
    {
        Cache::put($key->key, $value);
        $this->logger->log(LogLevels::Info, 'Data cached', ['key' => $key->key]);
    }

    public function forget(CacheKey $key): void
    {
        Cache::forget($key->key);
        $this->logger->log(LogLevels::Info, 'Data removed from cache', ['key' => $key->key]);
    }

    public function remember(CacheKey $key, Closure $callback): mixed
    {
        $value = Cache::rememberForever($key->key, $callback);
        $this->logger->log(LogLevels::Info, 'Data remembered', ['key' => $key->key]);

        return $value;
    }
}
