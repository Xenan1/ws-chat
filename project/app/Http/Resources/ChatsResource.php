<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Support\Collection;

class ChatsResource extends BaseJsonResource
{
    /**
     * @var Collection<User>
     */
    protected Collection $users;

    /**
     * @param Collection<User> $resource
     */
    public function __construct(Collection $resource)
    {
        $this->users = $resource;
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        return [
            'chats' => $this->users->map(function (User $user) {
                return new UserResource($user);
            })
        ];
    }
}
