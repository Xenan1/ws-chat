<?php

namespace App\Services;

use App\DTO\DeviceTokenDataDTO;
use App\Models\DeviceToken;

class DeviceTokenService
{
    public function create(DeviceTokenDataDTO $deviceTokenDataD): DeviceToken
    {
        return DeviceToken::query()->create([
            'user_id' => $deviceTokenDataD->userId,
            'token' => $deviceTokenDataD->token,
        ]);
    }
}
