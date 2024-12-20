<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class CommonResponse extends JsonResponse
{
    public function __construct(bool $success, int $status)
    {
        $data = [
            'message' => $success ? 'success' : 'failure',
        ];

        parent::__construct($data, $status);
    }
}
