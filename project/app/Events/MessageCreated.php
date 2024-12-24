<?php

namespace App\Events;

use App\Models\Message;

class MessageCreated
{
    public function __construct(protected Message $message) {}

    public function getMessage(): Message
    {
        return $this->message;
    }
}
