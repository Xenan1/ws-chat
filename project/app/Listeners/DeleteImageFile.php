<?php

namespace App\Listeners;

use App\Events\ImageDeleted;
use App\Services\ImageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteImageFile implements ShouldQueue
{
    use Queueable;

    public function __construct(protected ImageService $imageService) {}

    public function handle(ImageDeleted $event): void
    {
        $this->imageService->deleteFile($event->getImage());
    }
}
