<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id');
            $table->foreignId('product_id');
            $table->string('product_name')->nullable();
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
        Schema::dropIfExists('sell_products');
    }
}
