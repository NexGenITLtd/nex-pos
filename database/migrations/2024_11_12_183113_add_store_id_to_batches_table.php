<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreIdToBatchesTable extends Migration
{
    public function up()
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->unsignedBigInteger('store_id')->nullable()->after('invoice_no');
        });
    }

    public function down()
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn('store_id');
        });
    }
};