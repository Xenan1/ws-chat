<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

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

    /**
     * @param User $user
     * @return Collection<User>
     */
    public function getUserSubscribers(User $user): Collection
    {
        #TODO: change stub when subscriptions ready
        return User::query()->inRandomOrder()->limit(2)->get();
    }
}
