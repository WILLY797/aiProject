<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function deliveryOptions()
    {
        return $this->hasMany(DeliveryOption::class);
    }
}
