<?php

namespace App\Services\Notifications;

use App\Models\User;
use Illuminate\Support\Collection;

abstract class AbstractNotificationService
{
    /**
     * @param Collection<User> $users
     * @param string $message
     * @return void
     */
    public function notifyUsers(Collection $users, string $message): void
    {
        foreach ($users as $user) {
            $this->notifyUser($user, $message);
        }
    }

    abstract protected function notifyUser(User $user, string $message): void;
}
