<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatPolicy
{
    public function get(User $user, Chat $chat): Response
    {
        return $chat->hasMember($user->id)
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
