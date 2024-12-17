<?php

namespace App\Services;

use App\DTO\MessageDataDTO;
use App\Models\Message;

class ChatService
{
    public function createMessage(MessageDataDTO $data): Message
    {
        return Message::query()->create([
            'sender_id' => $data->senderId,
            'recipient_id' => $data->recipientId,
            'text' => $data->message,
        ]);
    }
}
