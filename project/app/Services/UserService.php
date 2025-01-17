<?php

namespace App\Services;

use App\Models\User;

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

    public function subscribe(User $subscriber, int $authorId): void
    {
        $subscriber->subscriptions()->attach($authorId, ['created_at' => now()]);
    }

    public function unsubscribe(User $subscriber, int $authorId): void
    {
        $subscriber->subscriptions()->detach($authorId);
    }

    public function generateReferralLink(string $login): string
    {
        return md5($login);
    }

    public function setReferralLink(User $user, string $value): void
    {
        $user->referral_link = $value;
        $user->saveQuietly();
    }

    public function getReferralLinkUser(?string $referralLink): ?User
    {
        return User::query()->where('referral_link', '=', $referralLink)->first();
    }
}
