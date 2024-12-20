<?php

namespace App\Services\Notifications;

use App\Logging\Enum\LogLevels;
use App\Logging\NotificationsLogger;
use App\Models\User;

class LogNotificationService extends AbstractNotificationService
{
    public function __construct(protected NotificationsLogger $logger) {}


    protected function notifyUser(User $user, string $message): void
    {
        $this->logger->log(LogLevels::Info, 'Notification sent', [
            'user_id' => $user->id,
            'message' => $message
        ]);
    }
}
