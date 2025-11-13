<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'progress_percent',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recalculateProgress(): void
    {
        $this->loadMissing(['course', 'user']);

        $totalLessons = $this->course->lessons()->count();

        if ($totalLessons === 0) {
            $this->forceFill([
                'progress_percent' => 0,
                'completed_at' => null,
            ])->save();

            return;
        }

        $completedLessons = $this->user->lessonCompletions()
            ->whereHas('lesson.module', fn ($query) => $query->where('course_id', $this->course_id))
            ->count();

        $progress = (int) round(($completedLessons / $totalLessons) * 100);

        $this->forceFill([
            'progress_percent' => min(100, $progress),
            'completed_at' => $progress >= 100 ? now() : null,
        ])->save();
    }
}
