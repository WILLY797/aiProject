<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'address1',
        'postcode',
        'balance',
        'credit_limit',
        'is_cash_account',
        'is_default_cash_sale_account',
        'credit_pending',
        'last_updated',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'is_cash_account' => 'boolean',
        'is_default_cash_sale_account' => 'boolean',
        'credit_pending' => 'boolean',
        'last_updated' => 'datetime',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
