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
            $table->id();
            
            // Ensure store_id is nullable and correctly references stores.id
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        
            // Correct nullOnDelete() usage (removed cascade)
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
        
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0); // Current balance
            
            // Ensure 'created_by' references 'users.id' correctly
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        
            $table->text('note')->nullable(); // Description of the transaction
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
        Schema::dropIfExists('transactions');
    }
};
