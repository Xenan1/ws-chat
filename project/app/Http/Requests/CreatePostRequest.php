<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'min:2'],
            'tags' => ['nullable', 'sometimes', 'array'],
            'tags.*' => ['exists:tags,id'],
        ];
    }

    public function getText(): string
    {
        return $this->input('text');
    }

    public function getTags(): array
    {
        return $this->input('tags') ?? [];
    }
}
