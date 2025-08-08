<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoxPriceBreaksTable extends Migration
{
    public function up()
    {
        Schema::create('equinox_price_breaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('equinox_products')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('equinox_accounts')->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('price', 15, 4);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equinox_price_breaks');
    }
}
