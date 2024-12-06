<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sell_products', function (Blueprint $table) {
            $table->float('purchase_price')->default(0)->after('product_name');
        });
    }

    public function down()
    {
        Schema::table('sell_products', function (Blueprint $table) {
            $table->dropColumn('purchase_price');
        });
    }

};
