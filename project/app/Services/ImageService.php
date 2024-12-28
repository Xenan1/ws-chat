<?php

namespace App\Services;

use App\Interfaces\ImageableInterface;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function createFromPath(Model&ImageableInterface $imageable, string $path): Image
    {
        return Image::query()->create([
            'src' => $path,
            'imageable_type' => $imageable::class,
            'imageable_id' => $imageable->getId(),
        ]);
    }

    public function deleteFile(Image $image): void
    {
        Storage::disk('public')->delete($image->getSrc());
    }
}
