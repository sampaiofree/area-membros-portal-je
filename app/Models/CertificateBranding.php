<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CertificateBranding extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'front_background_path',
        'back_background_path',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getFrontBackgroundUrlAttribute(): ?string
    {
        return $this->front_background_path
            ? Storage::disk('public')->url($this->front_background_path)
            : null;
    }

    public function getBackBackgroundUrlAttribute(): ?string
    {
        return $this->back_background_path
            ? Storage::disk('public')->url($this->back_background_path)
            : null;
    }
}

