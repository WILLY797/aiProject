<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equinox_price_breaks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');     // FK to equinox_products.id
            $table->unsignedBigInteger('account_id');     // external Equinox accountId (int)
            $table->string('account_code', 100)->nullable();
            $table->unsignedInteger('quantity');          // break qty
            $table->decimal('price', 15, 4);              // unit price at/above quantity
            $table->timestamps();

            $table->index('product_id');
            $table->index('account_id');
            $table->unique(['product_id', 'account_id', 'quantity'], 'pb_prod_acc_qty_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equinox_price_breaks');
    }
};
