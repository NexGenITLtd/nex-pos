<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockDateToBatchesTable extends Migration
{
    public function up()
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->date('stock_date')->nullable()->after('store_id');
        });
    }

    public function down()
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn('stock_date');
        });
    }
};
