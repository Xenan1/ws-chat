<?php

namespace App\Services;

use App\DTO\MessageDataDTO;
use App\Events\MessageCreated;
use App\Jobs\SendMessageByWebSocket;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class ChatService
{
    public function __construct(
        protected MessageEncryptor $encryptor,
        protected UploadService $uploadService,
        protected ImageService $imageService,
    ) {}

    public function createMessage(MessageDataDTO $data): Message
    {
        $message = Message::query()->create([
            'sender_id' => $data->senderId,
            'recipient_id' => $data->recipientId,
            'text' => $this->encryptor->encrypt($data),
        ]);

        event(new MessageCreated($message));

        return $message;
    }

    /**
     * @param int $userId
     * @param int $chatPartnerId
     * @return Collection<MessageDataDTO>
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
            })->orderBy('created_at')->with(['sender', 'recipient'])->get()->map(function (Message $message) {
                return new MessageDataDTO(
                    $this->encryptor->decryptMessageText($message->getText(), $message->getSender()->getId(), $message->getRecipient()->getId()),
                    $message->getSender()->getId(),
                    $message->getRecipient()->getId(),
                    $message->getSenderName(),
                    $message->getImageFullPath(),
                    $message->getCreatedAt(),
                );
            });
    }


    public function send(Message $message): void
    {
        $senderId = $message->getSender()->getId();
        $recipientId = $message->getRecipient()->getId();

        $encryptedMessageText = $this->encryptor->decryptMessageText(
            $message->getText(),
            $senderId,
            $recipientId
        );

        dispatch(new SendMessageByWebSocket(
            new MessageDataDTO(
                $encryptedMessageText,
                $senderId,
                $recipientId,
                $message->getSenderName(),
                $message->getImageFullPath(),
                $message->getCreatedAt(),
            )
        ));
    }

    /**
     * @param User $user
     * @return Collection<User>
     */
    public function getUserChats(User $user): Collection
    {
        return User::query()->whereNot('id', '=', $user->getId())->get();
    }

    public function setMessageAttachment(Message $message, UploadedFile $file): void
    {
        $imagePath = $this->uploadService->uploadImage($file, "/messages/$message->id/attachment");
        $this->imageService->createFromPath($message, $imagePath);
    }
}
