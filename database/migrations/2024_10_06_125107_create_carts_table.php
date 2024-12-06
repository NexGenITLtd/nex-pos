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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('store_id');
            $table->decimal('sell_price', 12, 2);
            $table->decimal('qty', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('vat', 12, 2)->default(0);
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
        Schema::dropIfExists('carts');
    }
};
