<?php

namespace App\DTO;

readonly class MessageDataDTO
{
    public function __construct(
        public string $message,
        public int $senderId,
        public int $recipientId,
        public ?string $createdAt = null,
    ) {}
}
