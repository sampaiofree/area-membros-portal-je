<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalTestAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_test_attempt_id',
        'final_test_question_id',
        'final_test_question_option_id',
        'is_correct',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(FinalTestAttempt::class, 'final_test_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(FinalTestQuestion::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(FinalTestQuestionOption::class, 'final_test_question_option_id');
    }
}
