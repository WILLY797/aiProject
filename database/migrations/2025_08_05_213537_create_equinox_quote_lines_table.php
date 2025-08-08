<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoxQuoteLinesTable extends Migration
{
    public function up()
    {
        Schema::create('equinox_quote_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained('equinox_quotes')->cascadeOnDelete();
            $table->integer('line_number')->nullable();
            $table->string('sku')->nullable();
            $table->string('description')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('unit_price', 15, 4)->nullable();
            $table->decimal('extended_price', 15, 4)->nullable();
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->integer('vat_code')->nullable();
            $table->decimal('vat', 15, 4)->nullable();
            $table->decimal('total', 15, 4)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equinox_quote_lines');
    }
}
