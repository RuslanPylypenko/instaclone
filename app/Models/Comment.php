<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $author_id
 * @property int $post_id
 * @property string $text
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Comment extends Model
{
    use HasFactory;
}
