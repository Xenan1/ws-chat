<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendNotificationByWebSocket implements ShouldBroadcast
{
    public function __construct(protected User $user, protected string $message) {}

    public function broadcastOn(): Channel
    {
        return new Channel('user.' . $this->user->getId());
    }

    public function broadcastAs(): string
    {
        return 'notification';
    }

    public function broadcastWith(): array
    {
        return [
            'type' => 'notification',
            'recipient' => $this->user->getId(),
            'text' => $this->message,
        ];
    }
}
