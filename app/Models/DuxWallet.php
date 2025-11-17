<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuxWallet extends Model
{
    use HasFactory;

    // Carteira de duxes por usuário. Guarda o saldo atual e expõe o histórico via transactions.
    protected $fillable = [
        'user_id',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(DuxTransaction::class, 'wallet_id');
    }
}
