<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinalTestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_test_id',
        'user_id',
        'score',
        'passed',
        'started_at',
        'submitted_at',
        'attempted_at',
    ];

    protected function casts(): array
    {
        return [
            'passed' => 'boolean',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'attempted_at' => 'datetime',
        ];
    }

    public function finalTest(): BelongsTo
    {
        return $this->belongsTo(FinalTest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(FinalTestAnswer::class, 'final_test_attempt_id');
    }

    public function remainingSeconds(): ?int
    {
        if (! $this->started_at || $this->submitted_at) {
            return null;
        }

        $duration = $this->finalTest->duration_minutes;

        if (! $duration) {
            return null;
        }

        $deadline = $this->started_at->copy()->addMinutes($duration);

        return max(0, $deadline->diffInSeconds(now(), false) * -1);
    }
}
