<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'login' => ['string', 'required'],
            'password' => ['string', 'required', 'min:8'],
        ];
    }

    public function getLogin(): string
    {
        return $this->input('login');
    }

    public function getPassword(): string
    {
        return $this->input('password');
    }
}
