<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierReturnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_return_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->default(0);
            $table->foreignId('product_id');
            $table->foreignId('supplier_id');
            $table->float('purchase_price');
            $table->float('sell_price');
            $table->date('return_date');
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
        Schema::dropIfExists('supplier_return_products');
    }
}
