<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuxTransaction extends Model
{
    use HasFactory;

    // Histórico de movimentações de duxes de uma carteira, vinculado a uma regra opcional e metadados.
    protected $fillable = [
        'wallet_id',
        'admin_id',
        'rule_id',
        'direction',
        'amount',
        'source',
        'reason',
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
