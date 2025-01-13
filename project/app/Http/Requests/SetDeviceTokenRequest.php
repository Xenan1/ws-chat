<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetDeviceTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_token' => ['required', 'string'],
        ];
    }

    public function getDeviceToken(): string
    {
        return $this->input('device_token');
    }
}
