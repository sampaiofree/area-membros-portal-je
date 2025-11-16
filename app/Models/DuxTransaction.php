<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuxTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'rule_id',
        'direction',
        'amount',
        'source',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function wallet()
    {
        return $this->belongsTo(DuxWallet::class, 'wallet_id');
    }

    public function rule()
    {
        return $this->belongsTo(DuxRule::class, 'rule_id');
    }
}
