<?php

namespace App\Listeners;

use App\Events\PostPublished;
use App\Services\Notifications\AbstractNotificationService;
use App\Services\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPostNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected UserService $userService,
        protected AbstractNotificationService $notificationService,
    ) {}

    /**
     * Handle the event.
     */
    public function handle(PostPublished $event): void
    {
        $author = $event->getPost()->getAuthor();
        $users = $this->userService->getUserSubscribers($author);
        $message = "User {$author->getName()} published new post";
        $this->notificationService->notifyUsers($users, $message);
    }
}
