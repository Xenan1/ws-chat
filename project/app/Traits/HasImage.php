<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasImage
{
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getImageSrc(): ?string
    {
        return $this->image
            ? $this->image->getSrc()
            : null;
    }

    public function getImageFullPath(): ?string
    {
        $src = $this->getImageSrc();

        return $src
            ? config('app.domain') . '/storage/'. $src
            : null;
    }
}
