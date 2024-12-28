<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UploadImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => ['image', 'required'],
        ];
    }

    public function getImage(): UploadedFile
    {
        return $this->file('image');
    }
}
