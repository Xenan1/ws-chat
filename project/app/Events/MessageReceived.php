<?php

namespace App\Events;

use App\DTO\MessageDTO;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        protected MessageDTO $message,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('user.' . $this->message->chat->getId());
    }

    public function broadcastAs(): string
    {
        return 'message.received';
    }

    public function broadcastWith(): array
    {
        return [
            'sender' => $this->message->sender->getName(),
            'recipients' => $this->message->chat->getMembersExcept($this->message->sender->getId()),
            'text' => $this->message->text,
            'date' => $this->message->createdAt,
            'image' => $this->message->imagePath,
            'type' => 'message',
        ];
    }
}
