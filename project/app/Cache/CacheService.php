<?php

namespace App\Cache;

use App\Logging\CacheLogger;
use App\Logging\Enum\LogLevels;
use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function __construct(protected CacheLogger $logger) {}

    public function put(CacheKey $key, mixed $value, int|Carbon|null $ttl = null): void
    {
        Cache::put($key->key, $value, $ttl);
        $this->logger->log(LogLevels::Info, 'Data cached', ['key' => $key->key]);
    }

    public function forget(CacheKey $key): void
    {
        Cache::forget($key->key);
        $this->logger->log(LogLevels::Info, 'Data removed from cache', ['key' => $key->key]);
    }

    public function remember(CacheKey $key, Closure $callback, int|Carbon|null $ttl = null): mixed
    {
        $value = Cache::remember($key->key, $ttl, $callback);
        $this->logger->log(LogLevels::Info, 'Data remembered', ['key' => $key->key]);

        return $value;
    }

    public function has(CacheKey $key): bool
    {
        return Cache::has($key->key);
    }
}
