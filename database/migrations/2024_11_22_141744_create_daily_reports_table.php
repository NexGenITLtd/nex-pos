<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade'); // Add foreign key reference to stores table
            $table->date('date');
            $table->decimal('total_invoices', 10, 2);
            $table->decimal('previous_cash_in_hand', 10, 2)->default(0);
            $table->decimal('extra_cash', 10, 2)->default(0);
            $table->decimal('total_sales', 10, 2);
            $table->decimal('total_return_sell', 10, 2);
            $table->decimal('total_purchase_price', 10, 2);
            $table->decimal('total_profit', 10, 2);
            $table->decimal('total_due', 10, 2);
            $table->decimal('total_supplier_payment', 10, 2);
            $table->decimal('total_expense', 10, 2);
            $table->decimal('total_salary', 10, 2);
            $table->decimal('extra_expense', 10, 2)->default(0);
            $table->decimal('owner_deposit', 10, 2)->default(0);
            $table->decimal('bank_deposit', 10, 2)->default(0);
            $table->decimal('cash_in_hand', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_reports');
    }

};
