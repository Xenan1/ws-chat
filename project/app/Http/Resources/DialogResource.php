<?php

namespace App\Http\Resources;

use App\DTO\DialogDTO;
use App\Models\Message;
use Illuminate\Http\Request;

class DialogResource extends BaseJsonResource
{
    protected DialogDTO $dialog;

    public function __construct(DialogDTO $resource)
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
            'avatar' => $this->dialog->avatar?->getSrc(),
            'name' => $this->dialog->chatPartner->name,
            'messages' => $this->dialog->messages->map(function (Message $message) {
                return [
                    'name' => $message->getSenderName(),
                    'text' => $message->getText(),
                    'datetime' => $message->getCreatedAt(),
                ];
            })
        ];
    }
}
