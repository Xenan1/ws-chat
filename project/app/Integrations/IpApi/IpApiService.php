<?php

namespace App\Integrations\IpApi;

use App\DTO\CoordsDTO;
use Illuminate\Http\Client\RequestException;

class IpApiService
{
    public function __construct(protected IpApiClient $client) {}

    /**
     * @throws RequestException
     */
    public function getIpCoords(string $ip): CoordsDTO
    {
        $ipData = $this->client->getIpData($ip, config('integrations.ip-api.fields_set'));

        return new CoordsDTO($ipData['lat'], $ipData['lon']);
    }
}
