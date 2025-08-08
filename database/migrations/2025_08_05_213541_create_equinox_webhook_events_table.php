<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquinoxWebhookEventsTable extends Migration
{
    public function up()
    {
        Schema::create('equinox_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('equinox_event_id')->unique();
            $table->string('type')->index();
            $table->json('payload');
            $table->timestamp('received_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equinox_webhook_events');
    }
}
