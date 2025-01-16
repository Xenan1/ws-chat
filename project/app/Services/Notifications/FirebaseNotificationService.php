<?php

namespace App\Services\Notifications;

use App\Integrations\Google\GoogleService;
use App\Jobs\SendNotificationByFirebase;
use App\Logging\Enum\LogLevels;
use App\Logging\NotificationsLogger;
use App\Models\User;
use Google\Exception;

class FirebaseNotificationService extends AbstractNotificationService
{
    public function __construct(
        protected GoogleService $googleService,
        protected NotificationsLogger $logger,
    ) {}

    protected function notifyUser(User $user, string $message): void
    {
        try {
            $this->googleService->sendFirebaseNotification($user, 'Новое уведомление', $message);
        } catch (Exception $e) {
            $this->logger->log(LogLevels::Error, 'Firebase notification error: ' . $e->getMessage());
        }
    }
}
