<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoxAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('equinox_accounts', function (Blueprint $table) {
            $table->id();

            // FK (safer shorthand prevents errno 150)
            $table->foreignId('customer_id')
                ->constrained('equinox_customers')
                ->cascadeOnDelete();

            $table->string('equinox_id')->unique(); // external ID (string for safety)
            $table->string('code')->nullable();
            $table->string('name');

            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('address4')->nullable();
            $table->string('postcode')->nullable();

            $table->decimal('balance', 15, 4)->default(0);
            $table->decimal('credit_limit', 15, 4)->default(0);
            $table->date('last_updated')->nullable();
            $table->boolean('is_default_cash_sale_account')->default(false);

            $table->decimal('current', 15, 4)->nullable();
            $table->decimal('current_minus_1', 15, 4)->nullable();
            $table->decimal('current_minus_2', 15, 4)->nullable();
            $table->decimal('current_minus_3_and_prior', 15, 4)->nullable();
            $table->decimal('overdue', 15, 4)->nullable();

            $table->string('account_status')->nullable();
            $table->integer('terms_period_value')->nullable();
            $table->string('terms_period')->nullable();
            $table->decimal('settlement_percent', 7, 4)->nullable(); // avoid float
            $table->integer('settlement_period_value')->nullable();
            $table->string('settlement_period')->nullable();

            $table->json('metadata')->nullable();
            $table->json('raw_data')->nullable();

            $table->timestamps();

            // helpful indexes
            $table->index('code');
            $table->index('name');
            $table->index('postcode');
        });
    }

    public function down()
    {
        Schema::dropIfExists('equinox_accounts');
    }
}
