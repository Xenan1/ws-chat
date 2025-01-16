<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Logging\AuthLogger;
use App\Logging\Enum\LogLevels;
use App\Models\User;

class RegisterService
{
    public function __construct(protected AuthLogger $logger) {}

    public function registerUser(UserDTO $user): void
    {
        $user = User::query()->create($user->toCreatableArray());

        $this->logger->log(LogLevels::Info, 'User created', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_login' => $user->login
        ]);
    }
}
