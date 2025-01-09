<?php

namespace App\Http\Resources;

use App\DTO\ChatDTO;
use App\DTO\MessageDataDTO;
use App\Models\Message;
use Illuminate\Http\Request;

class DialogResource extends BaseJsonResource
{
    protected ChatDTO $dialog;

    public function __construct(ChatDTO $resource)
    {
        $this->dialog = $resource;
        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'avatar' => $this->dialog->chat->getImageFullPath(),
            'name' => $this->dialog->chat->getName(),
            'messages' => $this->dialog->messages->map(function (MessageDataDTO $message) {
                return [
                    'name' => $message->senderName,
                    'text' => $message->message,
                    'datetime' => $message->createdAt,
                    'image' => $message->imagePath,
                ];
            })
        ];
    }
}
