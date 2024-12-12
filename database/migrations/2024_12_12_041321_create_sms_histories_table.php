<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_histories', function (Blueprint $table) {
            $table->id();
            $table->string('type');  // To define the type of message (Invoice, Alert, etc.)
            $table->string('message'); // Store the message content
            $table->integer('sms_parts'); // Number of SMS parts
            $table->decimal('sms_cost', 8, 2); // Cost of the SMS
            $table->json('response'); // API response stored as JSON
            $table->string('recipient'); // Who received the SMS (e.g., customer phone)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_histories');
    }
};
