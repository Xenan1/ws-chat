<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;

class UserResource extends BaseJsonResource
{
    protected User $user;

    public function __construct(User $resource)
    {
        $this->user = $resource;
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
            'id' => $this->user->getId(),
            'name' => $this->user->getName(),
            'avatar' => $this->user->getAvatar()?->getSrc(),
        ];
    }
}
