<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnerDepositsTable extends Migration
{
    public function up()
    {
        Schema::create('owner_deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->date('date');
            $table->unsignedBigInteger('bank_account_id');
            $table->decimal('amount', 10, 2);
            $table->enum('transaction_type', ['deposit', 'withdrawal'])->default('deposit'); // New field
            $table->text('note')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('owner_deposits');
    }
}
