<?php

namespace App\Listeners;

use App\Cache\CacheKeyStorage;
use App\Cache\CacheService;
use App\Events\MessageCreated;

class ForgetDialogCache
{
    public function __construct(protected CacheService $cache) {}

    public function handle(MessageCreated $event): void
    {
        $this->cache->forget(CacheKeyStorage::dialog(
            $event->getMessage()->getRecipient()->getId(),
            $event->getMessage()->getSender()->getId()
        ));
    }
}
