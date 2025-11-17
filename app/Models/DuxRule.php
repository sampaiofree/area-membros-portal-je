<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuxRule extends Model
{
    use HasFactory;

    // Regras que geram créditos/débitos de duxes, com condições e direção configuráveis.
    protected $fillable = [
        'name',
        'slug',
        'direction',
        'amount',
        'type',
        'model',
        'conditions',
        'active',
    ];

    protected $casts = [
        'conditions' => 'array',
        'active' => 'boolean',
    ];
}
