<?php

namespace App\Http\Resources;

class CommonResponseResource extends BaseJsonResource
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
