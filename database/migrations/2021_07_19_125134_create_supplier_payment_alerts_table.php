<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierPaymentAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_payment_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id');
            $table->foreignId('supplier_id');
            $table->date('notice_date');
            $table->date('pay_date');
            $table->float('amount');
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
        Schema::dropIfExists('supplier_payment_alerts');
    }
}
