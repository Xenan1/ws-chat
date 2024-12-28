<?php

namespace App\Integrations\IpApi;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class IpApiClient
{
    /**
     * @throws RequestException
     */
    public function getIpData(string $ip, string $fieldsSet): array
    {
        $url = config('integrations.ip-api.url') . $ip;

        $response = Http::get($url, [
            'fields' => $fieldsSet
        ]);

        $response->throw();

        $body = json_decode($response->body(), true);

        if (!isset($body['status']) || $body['status'] === 'fail') {
            throw new RequestException($response);
        }

        return $body;
    }
}
