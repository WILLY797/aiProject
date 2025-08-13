<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'equinox_orders';

    protected $fillable = [
        'user_id',
        'account_id',
        'status',
        'total',
        'delivery_option_id',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price');
    }

    public function deliveryOption()
    {
        return $this->belongsTo(DeliveryOption::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
