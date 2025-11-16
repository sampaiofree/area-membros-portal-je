<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuxPack extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duxes',
        'price_cents',
        'currency',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
