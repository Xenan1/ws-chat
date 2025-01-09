<?php

namespace App\DTO;

use App\Models\Image;

readonly class MessageDataDTO
{
    public function __construct(
        public string  $message,
        public int     $senderId,
        public int     $chatId,
        public ?string $senderName = null,
        public ?string $imagePath = null,
        public ?string $createdAt = null,
    ) {}
}
