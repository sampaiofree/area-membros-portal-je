<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Kavoo extends Model
{
    use HasFactory;

    protected $table = 'kavoo';

    protected $fillable = [
        'customer_name',
        'customer_first_name',
        'customer_last_name',
        'customer_email',
        'customer_phone',
        'item_product_id',
        'item_product_name',
        'transaction_code',
        'status_code',
        'customer',
        'address',
        'items',
        'affiliate',
        'transaction',
        'payment',
        'commissions',
        'shipping',
        'links',
        'tracking',
        'status',
        'recurrence',
    ];

    protected $casts = [
        'customer' => 'array',
        'address' => 'array',
        'items' => 'array',
        'affiliate' => 'array',
        'transaction' => 'array',
        'payment' => 'array',
        'commissions' => 'array',
        'shipping' => 'array',
        'links' => 'array',
        'tracking' => 'array',
        'status' => 'array',
        'recurrence' => 'array',
    ];

    public function customerEmailOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_email', 'email');
    }

    public function customerPhoneOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_phone', 'whatsapp');
    }

    public function getCustomerUserAttribute(): ?User
    {
        return $this->customerEmailOwner ?? $this->customerPhoneOwner;
    }

    public function scopeWithCustomerRelations(Builder $query): Builder
    {
        return $query->with(['customerEmailOwner', 'customerPhoneOwner']);
    }
}
