<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoxCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('equinox_customers', function (Blueprint $table) {
            $table->id();
            $table->string('equinox_id')->unique();
            $table->unsignedBigInteger('parent_customer_id')->nullable();
            $table->foreign('parent_customer_id')
                ->references('id')
                ->on('equinox_customers')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->json('billing_address')->nullable();
            $table->json('shipping_address')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equinox_customers');
    }
}
