<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->id(); // auto-incrementing primary key
            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('api_key')->nullable();
            $table->string('sender_id')->nullable();
            $table->string('message')->nullable();
            $table->string('user_email')->nullable();
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('sms_rate', 10, 2)->default(0);
            $table->integer('sms_count')->default(0);
            $table->timestamps(); // created_at, updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_settings');
    }
}
