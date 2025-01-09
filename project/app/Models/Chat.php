<?php

namespace App\Models;

use App\Interfaces\ImageableInterface;
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
        return $this->name;
    }

    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function getMembersExcept(int $userId): Collection
    {
        return $this->members()->whereNot('id', '=', $userId)->get();
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }
}
