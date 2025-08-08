<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchStocksTable extends Migration
{
    public function up()
    {
        Schema::create('branch_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('equinox_products')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->integer('stock')->default(0);
            $table->integer('unprocessed_order_qty')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('branch_stocks');
    }
}

