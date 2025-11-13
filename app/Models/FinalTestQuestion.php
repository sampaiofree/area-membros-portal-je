<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinalTestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_test_id',
        'title',
        'statement',
        'position',
        'weight',
    ];

    public function finalTest(): BelongsTo
    {
        return $this->belongsTo(FinalTest::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(FinalTestQuestionOption::class)->orderBy('position');
    }
}
