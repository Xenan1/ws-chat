<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'members' => ['required', 'array', 'min:1'],
            'members.*' => [Rule::exists('users', 'id')],
        ];
    }

    public function getName(): string
    {
        return $this->input('name');
    }

    public function getMembersIds(): array
    {
        return $this->input('members');
    }
}
