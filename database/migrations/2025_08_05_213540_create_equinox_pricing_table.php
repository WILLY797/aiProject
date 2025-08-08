<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoxPricingTable extends Migration
{
    public function up()
    {
        Schema::create('equinox_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('equinox_products')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('equinox_customers')->cascadeOnDelete();
            $table->decimal('price', 15, 4);
            $table->string('currency', 3);
            $table->timestamp('effective_date')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equinox_pricing');
    }
}
