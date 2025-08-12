<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;

    // DB table created by your migration
    protected $table = 'branch_stocks';

    protected $fillable = [
        'product_id',
        'branch_id',
        'stock',                   // renamed from quantity
        'unprocessed_order_qty',   // extra column in your schema
    ];

    protected $casts = [
        'product_id' => 'integer',
        'branch_id' => 'integer',
        'stock' => 'integer',
        'unprocessed_order_qty' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /* Scopes */
    public function scopeForBranch($q, int $branchId)
    {
        return $q->where('branch_id', $branchId);
    }

    public function scopeAvailable($q)
    {
        return $q->where('stock', '>', 0);
    }
}
