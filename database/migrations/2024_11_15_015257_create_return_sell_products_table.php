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
        Schema::create('return_sell_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id');
            $table->foreignId('store_id')->nullable();
            $table->foreignId('product_id');
            $table->string('product_name')->nullable();
            $table->float('purchase_price')->default(0);
            $table->float('sell_price')->default(0);
            $table->float('qty')->default(0);
            $table->float('vat')->default(0);
            $table->float('discount')->default(0);
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
        Schema::dropIfExists('return_sell_products');
    }
};
