<?php

namespace App\Http\Resources;

use App\Models\Chat;
use Illuminate\Support\Collection;

class ChatsResource extends BaseJsonResource
{
    /**
     * @var Collection<Chat>
     */
    protected Collection $chats;

    /**
     * @param Collection<Chat> $resource
     */
    public function __construct(Collection $resource)
    {
        $this->chats = $resource;
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        return [
            'chats' => ChatPreviewResource::collection($this->chats),
        ];
    }
}
