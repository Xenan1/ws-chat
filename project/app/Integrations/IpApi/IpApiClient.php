<?php

namespace App\Integrations\IpApi;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class IpApiClient
{
    /**
     * @throws RequestException
     */
    public function getIpData(string $ip): array
    {
        $url = config('integrations.ip-api.url') . $ip . '?fields=' . config('integrations.ip-api.fields_set');

        $response = Http::get($url);
        $response->throw();

        $body = json_decode($response->body(), true);

        if (!isset($body['status']) || $body['status'] === 'fail') {
            throw new RequestException($response);
        }

        return $body;
    }
}
