<?php

namespace App\Http\Resources;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    protected Chat $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
        parent::__construct($chat);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->chat->getId(),
            'name' => $this->chat->getName(),
            'avatar' => $this->chat->getImageFullPath(),
        ];
    }
}
