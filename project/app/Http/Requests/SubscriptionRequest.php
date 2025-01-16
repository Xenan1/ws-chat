<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author' => ['required', 'exists:users,id'],
        ];
    }

    public function getAuthorId(): int
    {
        return $this->input('author');
    }
}
