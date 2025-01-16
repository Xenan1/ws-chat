<?php

namespace App\Models;

use App\Interfaces\ImageableInterface;
use App\Traits\HasImage;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Platform\Models\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property string $login
 * @property string $password
 * @property ?Image $image
 * @property Collection<DeviceToken> $deviceTokens
 */
class User extends Authenticatable implements JWTSubject, ImageableInterface
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasImage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'login',
        'email',
        'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
           'id'         => Where::class,
           'name'       => Like::class,
           'email'      => Like::class,
           'updated_at' => WhereDateStartEnd::class,
           'created_at' => WhereDateStartEnd::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function likedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'likes');
    }

    public function viewedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'posts_views');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getAvatar(): ?Image
    {
        return $this->image;
    }

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    /**
     * @return Collection<DeviceToken>
     */
    public function getDeviceTokens(): Collection
    {
        return $this->deviceTokens;
    }
}
