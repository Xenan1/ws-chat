<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $text
 * @property User $author
 * @property string $created_at
 */
class Post extends Model
{
    protected $guarded = [];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'posts_tags');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function getLikesCount(): int
    {
        return $this->likes->count();
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
}
