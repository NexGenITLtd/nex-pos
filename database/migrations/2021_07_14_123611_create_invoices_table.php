<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id()->from(100001);
            $table->integer('customer_id')->default(0);
            $table->float('total_bill')->default(0);
            $table->float('paid_amount')->default(0);
            $table->float('due_amount')->default(0);
            $table->float('discount')->default(0);
            $table->float('less_amount')->default(0);
            $table->foreignId('manager_id');
            $table->foreignId('sell_person_id');
            $table->foreignId('store_id');
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
        Schema::dropIfExists('invoices');
    }
}
