<?php

namespace App\Http\Resources;

use App\DTO\DialogDTO;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DialogResource extends JsonResource
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
            'avatar' => $this->dialog->avatar?->src,
            'name' => $this->dialog->chatPartner->name,
            'messages' => $this->dialog->messages->map(function (Message $message) {
                return [
                    'name' => $message->sender->name,
                    'text' => $message->text,
                    'datetime' => $message->created_at,
                ];
            })
        ];
    }
}
