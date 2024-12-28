<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    public function uploadImage(UploadedFile $image, string $path): string
    {
        $path = '/' . trim($path, '/') . "/{$image->hashName()}";
        Storage::disk('public')->put($path, $image->get());

        return $path;
    }
}
