<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /** Equinox mirror table */
    protected $table = 'equinox_products';

    protected $fillable = [
        'equinox_id',
        'sku',
        'mpn',
        'name',
        'description',
        'base_price',
        'last_updated',
        'metadata',
        'is_active',        // keep if you use it in UI filters
    ];

    protected $casts = [
        'base_price' => 'decimal:4',
        'last_updated' => 'datetime',
        'metadata' => 'array',
        'is_active' => 'boolean',
    ];

    /** Per-branch stock rows (branch_stocks) */
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'product_id'); // Stock::$table = 'branch_stocks'
    }

    /** Optional: orders/quotes pivots if you use them */
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price');
    }

    public function quotes()
    {
        return $this->belongsToMany(Quote::class)->withPivot('quantity', 'price');
    }

    /** Helper scope for search */
    public function scopeSearch($query, ?string $q)
    {
        if (! empty($q)) {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhere('mpn', 'like', "%{$q}%");
            });
        }
        return $query;
    }
}
