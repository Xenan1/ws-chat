<?php

namespace App\Models;

use App\Interfaces\ImageableInterface;
use App\Services\ChatService;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property Collection<User> $members
 * @property Collection<Message> $messages
 */
class Chat extends Model implements ImageableInterface
{
    use HasImage;

    protected $guarded = [];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chats_users');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return app(ChatService::class)->getName($this);
    }

    public function getMembers(): Collection
    {
        return $this->members;
    }

    /**
     * @param int $userId
     * @return Collection<User>
     */
    public function getMembersExcept(int $userId): Collection
    {
        return $this->members()->whereNot('users.id', '=', $userId)->get();
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function isDialog(): bool
    {
        return $this->is_dialog;
    }

    public function hasMember(int $userId): bool
    {
        return $this->members()->where('users.id', '=', $userId)->exists();
    }
}
