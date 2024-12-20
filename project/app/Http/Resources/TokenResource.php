<?php

namespace App\Http\Resources;

use App\DTO\TokenDTO;
use Illuminate\Http\Request;

class TokenResource extends BaseJsonResource
{
    public function __construct(TokenDTO $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this->accessToken,
            'token_type' => $this->type,
            'expires_in' => $this->expireTime
        ];
    }
}
