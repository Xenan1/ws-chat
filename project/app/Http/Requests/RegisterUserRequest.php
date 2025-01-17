<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', Rule::unique('users', 'login')],
            'name' => ['required', 'string', 'min:2'],
            'password' => ['required', 'string', 'min:8'],
            'referral' => ['sometimes', 'nullable', 'string']
        ];
    }

    public function getLogin(): string
    {
        return $this->input('login');
    }

    public function getName(): string
    {
        return $this->input('name');
    }

    public function getPassword(): string
    {
        return $this->input('password');
    }

    public function getReferral(): ?string
    {
        return $this->input('referral');
    }
}
