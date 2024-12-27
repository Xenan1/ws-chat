<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface ImageableInterface
{
    public function image(): MorphOne;
    public function getId(): int;
}
