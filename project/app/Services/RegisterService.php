<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Logging\AuthLogger;
use App\Logging\Enum\LogLevels;
use App\Models\User;

class RegisterService
{
    public function __construct(protected AuthLogger $logger, protected UserService $userService) {}

    public function registerUser(UserDTO $user): void
    {
        $user = User::query()->create($user->toCreatableArray());
        $referralLink = $this->userService->generateReferralLink($user->getLogin());
        $this->userService->setReferralLink($user, $referralLink);

        $this->logger->log(LogLevels::Info, 'User created', [
            'user_id' => $user->getId(),
            'user_name' => $user->getName(),
            'user_login' => $user->getLogin(),
            'referral_link' => $user->getReferralLink(),
        ]);
    }
}
