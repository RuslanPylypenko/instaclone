<?php

namespace App\Models\User;

use App\Models\Post;
use App\Services\User\PasswordHasher;
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
 * @property null|string $confirm_token
 * @property null|string $reset_password_token
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

    //================================

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getNick(): string
    {
        return $this->nick;
    }

    public function setNick(string $nick): void
    {
        $this->nick = $nick;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(UserStatus $status): void
    {
        if ($this->status === $status->value) {
            throw new \LogicException(sprintf('Status is already set as %s', $status->value));
        }

        $this->status = $status->value;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): void
    {
        $this->bio = $bio;
    }

    public function getLastVisit(): \DateTime
    {
        return $this->last_visit;
    }

    public function setLastVisit(\DateTime $last_visit): void
    {
        $this->last_visit = $last_visit;
    }

    public function getBirthDate(): \DateTime
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTime $birth_date): void
    {
        $this->birth_date = $birth_date;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirm_token;
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

    public function confirm(): void
    {
        if ($this->confirm_token === null) {
            throw new \LogicException('User already confirmed.');
        }

        $this->confirm_token = null;
        $this->setStatus(UserStatus::CONFIRMED);
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
}
