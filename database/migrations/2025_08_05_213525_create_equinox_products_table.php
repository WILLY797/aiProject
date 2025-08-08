<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoxProductsTable extends Migration
{
    public function up()
    {
        Schema::create('equinox_products', function (Blueprint $table) {
            $table->id();
            $table->string('equinox_id')->unique();
            $table->string('sku')->nullable()->index();
            $table->string('mpn')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('base_price', 15, 4);
            $table->timestamp('last_updated')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equinox_products');
    }
}
