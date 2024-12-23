<?php

namespace App\Services\Notifications;

use App\Jobs\SendNotificationByWebSocket;
use App\Models\User;

class WebsocketNotificationService extends AbstractNotificationService
{
    protected function notifyUser(User $user, string $message): void
    {
        broadcast(new SendNotificationByWebSocket($user, $message));
    }
}
