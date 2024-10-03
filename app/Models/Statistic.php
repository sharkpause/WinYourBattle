<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_of_relapse',
        'timezone'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
