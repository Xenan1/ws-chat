<?php

namespace App\Http\Resources;

use App\DTO\ChatDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatPreviewResource extends JsonResource
{
    protected ChatDTO $chatDTO;

    public function __construct(ChatDTO $resource)
    {
        $this->chatDTO = $resource;
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->chatDTO->chat->getId(),
            'name' => $this->chatDTO->chat->getName(),
            'avatar' => $this->chatDTO->chat->getImageFullPath(),
        ];
    }
}
