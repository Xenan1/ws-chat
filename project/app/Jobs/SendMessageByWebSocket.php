<?php

namespace App\Jobs;

use App\Events\MessageReceived;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendMessageByWebSocket implements ShouldQueue
{
    use Queueable;

    public $queue = 'chat';

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Message $message,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        broadcast(new MessageReceived($this->message->sender, $this->message->recipient, $this->message));
    }
}
