<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Hashtag extends Model
{
    use HasFactory;
}
