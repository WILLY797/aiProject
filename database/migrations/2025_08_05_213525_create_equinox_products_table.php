<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoxProductsTable extends Migration
{
    public function up(): void
    {
        Schema::create('equinox_products', function (Blueprint $table) {
            $table->id();

            // External identifiers
            $table->string('equinox_id', 64)->unique()->index();
            $table->string('sku', 100)->nullable()->index();
            $table->string('mpn', 150)->nullable()->index();

            // Descriptive
            $table->string('name', 255)->index();
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('base_price', 15, 4)->default(0);

            // Sync/meta
            $table->timestamp('last_updated')->nullable()->index();
            $table->json('metadata')->nullable();

            $table->timestamps();

            // If you need to enforce uniqueness across vendor/sku, uncomment:
            // $table->unique(['sku', 'equinox_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equinox_products');
    }
}
