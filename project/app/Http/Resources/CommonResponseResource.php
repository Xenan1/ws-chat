<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommonResponseResource extends JsonResource
{
    protected bool $success;

    public function __construct(bool $success)
    {
        parent::__construct($success);
    }

    public function toArray($request): array
    {
        return [
            'message' => $this->success ? 'success' : 'failure',
        ];
    }
}
