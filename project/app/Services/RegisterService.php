<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;

class RegisterService
{
    public function registerUser(UserDTO $user): void
    {
        User::query()->create($user->toArray());
    }
}
