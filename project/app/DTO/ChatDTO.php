<?php

namespace App\DTO;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Collection;

readonly class ChatDTO
{
    public function __construct(
        public User $user,
        public Chat $chat,
        public Collection $messages
    ) {}
}
