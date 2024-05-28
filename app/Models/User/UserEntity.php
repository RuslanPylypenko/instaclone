<?php

namespace App\Models\User;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id,
 * @property string $email
 * @property string $password
 * @property string $nick
 * @property string $status
 * @property string $first_name
 * @property null|string $last_name
 * @property null|string $avatar
 * @property null|string $bio
 * @property \DateTime $last_visit
 * @property \DateTime $birth_date
 * @property string $confirm_token
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class UserEntity extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'password',
        'status',
        'nick',
        'first_name',
        'last_name',
        'avatar',
        'bio',
        'last_visit',
        'confirm_token',
        'birth_date',
    ];

    protected $hidden = [
        'password',
    ];

    public function setStatus(UserStatus $status): void
    {
        if ($this->status === $status->value) {
            throw new \LogicException(sprintf('Status is already set as %s', $status->value));
        }

        $this->status = $status->value;
    }

    public function confirm(): void
    {
        if ($this->confirm_token === null) {
            throw new \LogicException('User already confirmed.');
        }

        $this->confirm_token = null;
        $this->setStatus(UserStatus::CONFIRMED);
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

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
}
