<?php

namespace App\Http\Resources;

use App\DTO\MessageDataDTO;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    protected MessageDataDTO $messageDTO;
    public function __construct(MessageDataDTO $resource)
    {
        $this->messageDTO = $resource;
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        return [
            'name' => $this->messageDTO->senderName,
            'text' => $this->messageDTO->message,
            'datetime' => $this->messageDTO->createdAt,
            'image' => $this->messageDTO->imagePath,
        ];
    }
}
