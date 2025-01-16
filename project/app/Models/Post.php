<?php

namespace App\Models;

use App\Parsing\ParseSources;
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
 * @property ?string $parsed_id
 * @property ?string $parsed_source
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

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function getParsedId(): ?string
    {
        return $this->parsed_id;
    }

    public function getParsedSource(): ?ParseSources
    {
        return ParseSources::tryFrom($this->parsed_source);
    }
}
