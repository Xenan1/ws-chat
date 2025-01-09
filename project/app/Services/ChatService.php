<?php

namespace App\Services;

use App\DTO\MessageDataDTO;
use App\DTO\MessageDTO;
use App\Events\MessageCreated;
use App\Jobs\SendMessageByWebSocket;
use App\Models\Chat;
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
            'recipient_id' => $data->chatId,
            'text' => $this->encryptor->encrypt($data),
        ]);

        event(new MessageCreated($message));

        return $message;
    }

    /**
     * @param int $chatId
     * @return Collection<MessageDataDTO>
     */
    public function getDialogMessages(int $chatId): Collection
    {
        return Message::query()
            ->where('chat_id', '=', $chatId)
            ->orderBy('created_at')->with(['sender', 'chat'])->get()->map(function (Message $message) {
                return new MessageDataDTO(
                    $this->encryptor->decryptMessageText($message->getText(), $message->getChat()->getId()),
                    $message->getSender()->getId(),
                    $message->getChat()->getId(),
                    $message->getSenderName(),
                    $message->getImageFullPath(),
                    $message->getCreatedAt(),
                );
            });
    }


    public function send(Message $message): void
    {
        $sender = $message->getSender();
        $chat = $message->getChat();

        $decryptedMessageText = $this->encryptor->decryptMessageText(
            $message->getText(),
            $chat->getId(),
        );

        dispatch(new SendMessageByWebSocket(
            new MessageDTO(
                $decryptedMessageText,
                $sender,
                $chat,
                $message->getCreatedAt(),
                $message->getImageFullPath(),
            )
        ));
    }

    /**
     * @param User $user
     * @return Collection<Chat>
     */
    public function getUserChats(User $user): Collection
    {
        return Chat::query()->whereRelation('members', 'id', $user->getId())->get();
    }

    public function setMessageAttachment(Message $message, UploadedFile $file): void
    {
        $imagePath = $this->uploadService->uploadImage($file, "/messages/$message->id/attachment");
        $this->imageService->createFromPath($message, $imagePath);
    }
}
