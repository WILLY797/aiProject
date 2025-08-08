<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoxInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('equinox_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('equinox_accounts')->cascadeOnDelete();
            $table->string('equinox_id')->unique();
            $table->string('number')->nullable();
            $table->date('date')->nullable();
            $table->string('reference')->nullable();
            $table->integer('order_source')->nullable();
            $table->string('shipping_method')->nullable();
            $table->decimal('shipping_net_value', 15, 4)->nullable();
            $table->decimal('shipping_vat_rate_percent', 5, 2)->nullable();
            $table->decimal('total_net', 15, 4)->nullable();
            $table->decimal('total_vat', 15, 4)->nullable();
            $table->decimal('total_gross', 15, 4)->nullable();
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
        Schema::dropIfExists('equinox_invoices');
    }
}
