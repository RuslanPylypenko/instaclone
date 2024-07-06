<?php

namespace App\Models\User;

use App\Models\Post;
use App\Models\PostLike;
use App\Services\PasswordHasher;
use App\ValueObjects\TokenValueObject;
use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id,
 * @property string $email
 * @property string $password
 * @property string $nick
 * @property UserStatus $status
 * @property string $first_name
 * @property null|string $last_name
 * @property null|string $avatar
 * @property null|string $bio
 * @property DateTime $last_visit
 * @property DateTime $birth_date
 * @property null|string $confirm_token
 * @property null|string $reset_password_token
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime $deleted_at
 * @property DateTime $confirm_token_expires_at
 * @property TokenValueObject|null $confirmToken
 */
class UserEntity extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $guarded = [];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'last_visit' => 'datetime',
        'birth_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => UserStatus::class,
    ];

    //================================

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isWait(): bool
    {
        return $this->status === UserStatus::WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::ACTIVE;
    }

    public function confirm(): void
    {
        if ($this->confirm_token === null) {
            throw new \LogicException('User already confirmed.');
        }

        $this->confirm_token = null;
        $this->setStatus(UserStatus::ACTIVE);
    }

    public function setStatus(UserStatus $status): void
    {
        if ($this->status === $status) {
            throw new \LogicException(sprintf('Status is already set as %s', $status->value));
        }

        $this->status = $status;
    }

    public function setResetPasswordToken(string $token): void
    {
        if ($this->reset_password_token !== null) {
            throw new \LogicException('User already requested reset password.');
        }

        $this->reset_password_token = $token;
    }

    public function resetPassword(string $password, PasswordHasher $passwordHasher): void
    {
        $this->password = $passwordHasher->hash($password);
        $this->reset_password_token = null;
    }

    public function follow(UserEntity $follower): void
    {
        if ($this->followers()->find($follower->id)->exists()) {
            throw new \LogicException('User is already a follower.');
        }

        $this->followers()->attach($follower->id);
    }

    public function unfollow(UserEntity $follower): void
    {
        if (! $this->followers()->find($follower->id)->exists()) {
            throw new \LogicException('User is not a follower.');
        }

        $this->followers()->detach($follower->id);
    }

    //================================

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function followings()
    {
        return $this->belongsToMany(UserEntity::class, 'user_follows', 'follower_id', 'following_id');
    }

    public function followers()
    {
        return $this->belongsToMany(UserEntity::class, 'user_follows', 'following_id', 'follower_id');
    }

    public function isFollowing(UserEntity $otherUser)
    {
        return $this->followings()->where('following_id', $otherUser->id)->exists();
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class, 'user_id', 'id');
    }

    public function likedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'id');
    }

    protected function confirmToken(): Attribute
    {
        return Attribute::make(
            get: fn (string $value, array $attributes) => new TokenValueObject($attributes['confirm_token'], $attributes['confirm_token_expires_at']),
            set: fn (TokenValueObject $tokenValueObject) => [
                'confirm_token' => $tokenValueObject->token,
                'confirm_token_expires_at' => $tokenValueObject->expiresAt,
            ]
        );
    }
}
