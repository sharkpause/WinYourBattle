<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany(PostLike::class); 
    }
    public function dislikes()
    {
        return $this->hasMany(PostDislike::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
