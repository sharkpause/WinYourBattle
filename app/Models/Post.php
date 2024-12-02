<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use App\Models\PostLike;

use App\Models\PostDislike;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'image'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class); 
    }
    public function dislikes()
    {
        return $this->hasMany(PostDislike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
