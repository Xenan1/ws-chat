<?php

namespace App\Models;

use App\Events\ImageDeleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    protected $guarded = [];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getSrc(): string
    {
        return $this->src;
    }

    public static function boot(): void
    {
        static::deleted(function (self $image) {
            event(new ImageDeleted($image));
        });

        parent::boot();
    }
}
