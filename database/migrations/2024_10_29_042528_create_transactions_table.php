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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User reference
            $table->foreignId('store_id')->constrained()->onDelete('cascade'); // Store reference
            $table->decimal('debit', 15, 2)->default(0); // Debit amount
            $table->decimal('credit', 15, 2)->default(0); // Credit amount
            $table->decimal('balance', 15, 2)->default(0); // Balance after transaction
            $table->string('note')->nullable(); // Optional note for the transaction
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
