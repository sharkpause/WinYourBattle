<?php

namespace App\Models;

use App\Models\Like;
use App\Models\User;
use App\Models\Dislike;

use Illuminate\Database\Eloquent\Model;
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
        return $this->hasMany(Like::class); 
    }
    public function dislikes()
    {
        return $this->hasMany(Dislike::class);
    }
}
