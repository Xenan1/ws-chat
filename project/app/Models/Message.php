<?php

namespace App\Models;

use App\Interfaces\ImageableInterface;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property User $sender
 * @property Chat $chat
 * @property string $text
 * @property string $created_at
 * @property ?Image $image
 */
class Message extends Model implements ImageableInterface
{
    use HasImage;

    protected $guarded = [];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function getSender(): User
    {
        return $this->sender;
    }

    public function getSenderName(): string
    {
        return $this->getSender()->getName();
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }
}
