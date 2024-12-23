<?php

namespace App\Services;

use App\DTO\MessageDataDTO;
use App\Events\MessageCreated;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ChatService
{
    public function createMessage(MessageDataDTO $data): Message
    {
        $message = Message::query()->create([
            'sender_id' => $data->senderId,
            'recipient_id' => $data->recipientId,
            'text' => $data->message,
        ]);

        event(new MessageCreated($message));

        return $message;
    }

    /**
     * @param int $userId
     * @param int $chatPartnerId
     * @return Collection<Message>
     */
    public function getDialogMessages(int $userId, int $chatPartnerId): Collection
    {
        return Message::query()
            ->where(function (Builder $query) use ($userId, $chatPartnerId) {
                $query->where('sender_id', '=', $userId)
                    ->where('recipient_id', '=', $chatPartnerId);
            })->orWhere(function (Builder $query) use ($userId, $chatPartnerId) {
                $query->where('sender_id', '=', $chatPartnerId)
                    ->where('recipient_id', '=', $userId);
            })->orderBy('created_at')->with(['sender', 'recipient'])->get();
    }
}
