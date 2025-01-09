<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class UserService
{
    public function __construct(protected ImageService $imageService) {}

    public function likePost(User $user, int $postId): void
    {
        $user->likedPosts()->attach($postId);
    }

    public function unlikePost(User $user, int $postId): void
    {
        $user->likedPosts()->detach($postId);
    }

    public function setAvatar(User $user, string $imagePath): void
    {
        $user->image?->delete();
        $this->imageService->createFromPath($user, $imagePath);
    }
}
