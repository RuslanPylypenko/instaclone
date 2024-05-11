<?php

namespace App\Models\User;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id,
 * @property string $email
 * @property string $password
 * @property string $nick
 * @property string $first_name
 * @property null|string $last_name
 * @property null|string $avatar
 * @property null|string $bio
 * @property \DateTime $last_visit
 * @property \DateTime $birth_date
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class UserEntity extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

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
