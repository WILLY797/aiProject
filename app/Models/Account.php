<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $table = 'equinox_accounts';

    protected $fillable = [
        'customer_id',
        'equinox_id',
        'code',
        'name',
        'address1',
        'address2',
        'address3',
        'address4',
        'postcode',
        'balance',
        'credit_limit',
        'last_updated',
        'is_default_cash_sale_account',
        'current',
        'current_minus_1',
        'current_minus_2',
        'current_minus_3_and_prior',
        'overdue',
        'account_status',
        'terms_period_value',
        'terms_period',
        'settlement_percent',
        'settlement_period_value',
        'settlement_period',
        'metadata',
        'raw_data',
    ];

    protected $casts = [
        'balance' => 'decimal:4',
        'credit_limit' => 'decimal:4',
        'current' => 'decimal:4',
        'current_minus_1' => 'decimal:4',
        'current_minus_2' => 'decimal:4',
        'current_minus_3_and_prior' => 'decimal:4',
        'overdue' => 'decimal:4',
        'settlement_percent' => 'decimal:4',
        'is_default_cash_sale_account' => 'boolean',
        'last_updated' => 'date',
        'metadata' => 'array',
        'raw_data' => 'array',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

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
