<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceBreak extends Model
{
    protected $table = 'equinox_price_breaks';

    protected $fillable = [
        'product_id',        // local product.id (FK to equinox_products.id)
        'account_id',        // external accountId from Equinox (int)
        'account_code',      // external accountCode
        'quantity',          // break qty
        'price',             // unit price at/above quantity
    ];

    protected $casts = [
        'product_id' => 'integer',
        'account_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'decimal:4',
    ];
}
