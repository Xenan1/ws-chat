<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LikePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_id' => ['required', 'exists:posts,id'],
        ];
    }

    public function getPostId(): int
    {
        return $this->input('post_id');
    }
}
