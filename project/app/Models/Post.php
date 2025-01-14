<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $text
 * @property User $author
 * @property string $created_at
 * @property bool $approved
 */
class Post extends Model
{
    use HasFactory;

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

    public function getContent(string $key): mixed
    {
        return match ($key) {
            'author' => $this->author->getName(),
            default => $this->$key,
        };
    }

    public function approve(): void
    {
        $this->approved = true;
        $this->saveQuietly();
    }
}
