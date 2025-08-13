<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'equinox_customers';

    protected $fillable = [
        'equinox_id',
        'parent_customer_id',
        'user_id',
        'name',
        'email',
        'phone',
        'billing_address',
        'shipping_address',
        'metadata',
    ];

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'metadata' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parentCustomer()
    {
        return $this->belongsTo(Customer::class, 'parent_customer_id');
    }

    public function childCustomers()
    {
        return $this->hasMany(Customer::class, 'parent_customer_id');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class, 'customer_id');
    }
}
