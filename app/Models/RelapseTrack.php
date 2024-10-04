<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelapseTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'relapse_date',
        'streak_time'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
