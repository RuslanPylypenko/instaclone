<?php

namespace App\Models;

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
class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follows', 'follower_id', 'following_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follows', 'following_id', 'follower_id');
    }

    public function isFollowing(User $otherUser)
    {
        return $this->followings()->where('following_id', $otherUser->id)->exists();
    }
}
