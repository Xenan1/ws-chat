<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property bool $parsing_enabled
 */
class Config extends Model
{
    protected $guarded = [];

    public function isParsingEnabled(): bool
    {
        return $this->parsing_enabled;
    }

    public static function get(): static
    {
        return static::query()->first();
    }

}
