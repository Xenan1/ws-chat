<?php

namespace App\Integrations\IpApi;

use App\Integrations\IpApi\DTO\IpCoordsDTO;
use Illuminate\Http\Client\RequestException;

class IpApiService
{
    public function __construct(protected IpApiClient $client) {}

    /**
     * @throws RequestException
     */
    public function getIpCoords(string $ip): IpCoordsDTO
    {
        $ipData = $this->client->getIpData($ip);

        return new IpCoordsDTO($ipData['lat'], $ipData['lon']);
    }
}
