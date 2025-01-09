<?php

namespace App\DTO;

use App\Models\Chat;
use App\Models\User;

readonly class MessageDTO
{
    public function __construct(
        public string $text,
        public User $sender,
        public Chat $chat,
        public string $createdAt,
        public ?string $imagePath = null,

    ) {}
}
