<?php

namespace App\Models;

use App\Models\User\UserEntity;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string $token
 * @property string $text
 * @property int $authorId
 * @property Collection<PostLike> $likes
 * @property DateTime $createDate
 * @property DateTime $updateDate
 * @property UserEntity $author
 * @property Carbon $created_at
 */
class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function author(): BelongsTo
    {
        return $this->belongsTo(UserEntity::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function hashTags(): BelongsToMany
    {
        return $this->belongsToMany(HashTag::class, 'post_hashtag');
    }
}
