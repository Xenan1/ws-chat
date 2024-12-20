<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseJsonResource extends JsonResource
{
    public static function withoutWrapping(): bool
    {
        return true;
    }
}
