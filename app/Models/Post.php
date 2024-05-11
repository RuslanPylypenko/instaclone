<?php

namespace App\Models;

use App\Models\User\UserEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $token
 * @property string $text
 * @property string
 * @property int $authorId
 * @property int $likes
 * @property \DateTime $createDate
 * @property \DateTime $updateDate
 */
class Post extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(UserEntity::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function hashTags(): BelongsToMany
    {
        return $this->belongsToMany(Hashtag::class, 'post_hashtag');
    }
}
