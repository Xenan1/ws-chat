<?php

namespace App\Models;

use App\Interfaces\ImageableInterface;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property User $sender
 * @property User $recipient
 * @property string $text
 * @property string $created_at
 */
class Message extends Model implements ImageableInterface
{
    use HasImage;

    protected $guarded = [];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function getSender(): User
    {
        return $this->sender;
    }

    public function getSenderName(): string
    {
        return $this->getSender()->getName();
    }

    public function getRecipient(): User
    {
        return $this->recipient;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
}
