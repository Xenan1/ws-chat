<?php

namespace App\Cache;

class CacheKeyStorage
{
    public static function chat(int $chatId): CacheKey
    {
        return new CacheKey(sprintf('chat_%d_messages', $chatId));
    }

    public static function ipWeather(string $ip): CacheKey
    {
        return new CacheKey($ip . '_weather');
    }

    public static function proxy(string $proxyUrl): CacheKey
    {
        return new CacheKey('error_proxy_' . $proxyUrl);
    }
}
