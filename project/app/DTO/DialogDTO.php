<?php

namespace App\DTO;

use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Collection;

readonly class DialogDTO
{
    public function __construct(
        public User $user,
        public User $chatPartner,
        public Collection $messages,
        public ?Image $avatar
    ) {}
}
