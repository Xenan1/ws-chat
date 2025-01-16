<?php

namespace App\Http\Resources;

use App\DTO\ChatDTO;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    protected ChatDTO $chatDTO;

    public function __construct(ChatDTO $chat)
    {
        $this->chatDTO = $chat;
        parent::__construct($chat);
    }

    public function toArray($request): array
    {
        return [
            'chat' => new ChatPreviewResource($this->chatDTO),
            'members' => $this->when(!$this->chatDTO->chat->isDialog(), UserResource::collection($this->chatDTO->chat->getMembers())),
            'messages' => MessageResource::collection($this->chatDTO->messages)
        ];
    }
}
