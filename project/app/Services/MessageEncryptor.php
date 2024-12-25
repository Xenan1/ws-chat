<?php

namespace App\Services;

use App\DTO\MessageDataDTO;

class MessageEncryptor
{
    public function __construct(protected CryptoService $cryptoService) {}

    public function encrypt(MessageDataDTO $message): string
    {
        return $this->cryptoService->encrypt($message->message, $this->getDialogUniqueKey($message->senderId, $message->recipientId));
    }

    public function decrypt(MessageDataDTO $message): string
    {
        return $this->cryptoService->decrypt($message->message, $this->getDialogUniqueKey($message->senderId, $message->recipientId));
    }

    public function decryptMessageText(string $text, int $senderId, int $recipientId): string
    {
        return $this->cryptoService->decrypt($text, $this->getDialogUniqueKey($senderId, $recipientId));
    }

    protected function getDialogUniqueKey(int $userId, int $chatPartnerId): string
    {
        $usersIds = [$userId, $chatPartnerId];
        sort($usersIds);

        return md5(implode('_', $usersIds));
    }
}
