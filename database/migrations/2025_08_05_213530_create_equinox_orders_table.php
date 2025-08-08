<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoxOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('equinox_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('equinox_accounts')->cascadeOnDelete();
            $table->string('equinox_id')->unique();
            $table->string('order_number')->nullable();
            $table->string('web_order_id')->nullable();
            $table->string('reference')->nullable();
            $table->string('status')->index();
            $table->decimal('goods_total_net', 15, 4)->nullable();
            $table->decimal('goods_total_vat', 15, 4)->nullable();
            $table->decimal('order_total_gross', 15, 4)->nullable();
            $table->string('shipping_method')->nullable();
            $table->decimal('shipping_net_value', 15, 4)->nullable();
            $table->integer('shipping_vat_code')->nullable();
            $table->integer('payment_method')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_tel')->nullable();
            $table->text('notes')->nullable();
            $table->json('shipping_address')->nullable();
            $table->json('billing_address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equinox_orders');
    }
}
