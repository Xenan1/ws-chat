<?php

namespace App\Jobs;

use App\DTO\MessageDataDTO;
use App\DTO\MessageDTO;
use App\Events\MessageReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendMessageByWebSocket implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected MessageDTO $message,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        broadcast(new MessageReceived($this->message));
    }

    public function onQueue($queue): static
    {
        $this->queue = 'chat';

        return $this;
    }
}
