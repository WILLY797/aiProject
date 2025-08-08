<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoxQuotesTable extends Migration
{
    public function up()
    {
        Schema::create('equinox_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('equinox_accounts')->cascadeOnDelete();
            $table->string('equinox_id')->unique();
            $table->string('number')->nullable();
            $table->date('date')->nullable();
            $table->string('reference')->nullable();
            $table->decimal('value', 15, 4)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equinox_quotes');
    }
}
