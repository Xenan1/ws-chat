<?php

namespace App\Services;

use App\DTO\MessageDataDTO;

class MessageEncryptor
{
    public function __construct(protected CryptoService $cryptoService) {}

    public function encrypt(MessageDataDTO $message): string
    {
        return $this->cryptoService->encrypt($message->message, $this->getDialogUniqueKey($message->chatId));
    }

    public function decrypt(MessageDataDTO $message): string
    {
        return $this->cryptoService->decrypt($message->message, $this->getDialogUniqueKey($message->chatId));
    }

    public function decryptMessageText(string $text, int $chatId): string
    {
        return $this->cryptoService->decrypt($text, $this->getDialogUniqueKey($chatId));
    }

    protected function getDialogUniqueKey(int $chatId): string
    {
        return md5($chatId);
    }
}
