<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoxOrderErrorsTable extends Migration
{
    public function up()
    {
        Schema::create('equinox_order_errors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('equinox_orders')->cascadeOnDelete();
            $table->string('error_code');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equinox_order_errors');
    }
}
