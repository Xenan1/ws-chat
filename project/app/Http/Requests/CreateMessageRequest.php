<?php

namespace App\Http\Requests;

use App\DTO\MessageDataDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class CreateMessageRequest extends FormRequest
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
            'message' => ['required', 'string'],
            'chat_id' => ['required', Rule::exists('chats', 'id')],
            'image' => ['sometimes', 'nullable', 'image'],
        ];
    }

    public function getMessageData(): MessageDataDTO
    {
        return new MessageDataDTO(
            $this->input('message'),
            auth()->user()->getId(),
            $this->input('chat_id'),
        );
    }

    public function getImage(): ?UploadedFile
    {
        return $this->file('image');
    }
}
