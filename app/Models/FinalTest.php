<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinalTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'instructions',
        'passing_score',
        'max_attempts',
        'duration_minutes',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(FinalTestAttempt::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(FinalTestQuestion::class)->orderBy('position');
    }
}
