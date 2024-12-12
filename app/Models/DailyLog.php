<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'mood',
        'journal'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
