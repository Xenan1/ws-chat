<?php

namespace App\Events;

use App\Models\Image;

class ImageDeleted
{
    public function __construct(protected Image $image) {}

    public function getImage(): Image
    {
        return $this->image;
    }
}
