<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function likePost(User $user, int $postId): void
    {
        $user->likedPosts()->attach($postId);
    }

    public function unlikePost(User $user, int $postId): void
    {
        $user->likedPosts()->detach($postId);
    }
}
