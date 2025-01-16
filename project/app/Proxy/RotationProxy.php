<?php

namespace App\Proxy;

use App\Cache\CacheKeyStorage;
use App\Cache\CacheService;
use App\Logging\Enum\LogLevels;
use App\Logging\ProxyLogger;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RotationProxy
{
    public function __construct(protected ProxyLogger $logger, protected CacheService $cache) {}

    public function get(string $url): Response
    {
        foreach (config('proxy.urls') as $proxy) {

            if ($this->isProxyHasError($proxy)) {
                continue;
            }

            $this->logger->log(LogLevels::Info, 'Trying proxy', ['proxy' => $proxy]);

            try {
                $response = $this->fetchUrlWithProxy($proxy, $url);
            } catch (ConnectionException $e) {
                $this->logger->log(LogLevels::Warning, 'Got error on proxy: ' . $e->getMessage(), ['proxy' => $proxy]);
                $this->cacheErrorProxy($proxy);
                continue;
            }

            if ($response->status() == 403) {
                $this->logger->log(LogLevels::Warning, 'Got 403 on proxy, switching', ['proxy' => $proxy]);
                $this->cacheErrorProxy($proxy);
                continue;
            }

            $this->logger->log(LogLevels::Info, 'Success with proxy', ['proxy' => $proxy]);

            return $response;
        }

        $this->logger->log(LogLevels::Warning, 'All proxies banned, disable proxy');
        return Http::get($url);
    }

    /**
     * @throws ConnectionException
     */
    protected function fetchUrlWithProxy(mixed $proxy, string $url): Response
    {
        return Http::withOptions([
            'proxy' => [
                'http' => $proxy,
                'https' => $proxy,
            ],
        ])->get($url);
    }

    protected function cacheErrorProxy(mixed $proxy): void
    {
        $this->cache->put(CacheKeyStorage::proxy($proxy), true, now()->addMinutes(5));
    }

    protected function isProxyHasError(string $proxy): bool
    {
        return $this->cache->has(CacheKeyStorage::proxy($proxy));
    }
}
