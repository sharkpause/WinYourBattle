<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use App\Models\CommentLike;
use App\Models\CommentDislike;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'post_id',
        'body'
    ];

    public function likes()
    {
        return $this->hasMany(CommentLike::class); 
    }
    public function dislikes()
    {
        return $this->hasMany(CommentDislike::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
