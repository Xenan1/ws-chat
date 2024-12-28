<?php

namespace App\Providers;

use App\Events\ImageDeleted;
use App\Events\MessageCreated;
use App\Events\PostPublished;
use App\Listeners\DeleteImageFile;
use App\Listeners\ForgetDialogCache;
use App\Listeners\SendPostNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PostPublished::class => [
            SendPostNotification::class,
        ],

        MessageCreated::class => [
            ForgetDialogCache::class,
        ],

        ImageDeleted::class => [
            DeleteImageFile::class,
        ],
    ];
}
