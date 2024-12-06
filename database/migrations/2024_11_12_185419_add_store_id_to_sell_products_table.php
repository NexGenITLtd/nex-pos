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
        Schema::table('sell_products', function (Blueprint $table) {
            $table->foreignId('store_id')->after('discount')->nullable();
        });
    }

    public function down()
    {
        Schema::table('sell_products', function (Blueprint $table) {
            $table->dropColumn('store_id');
        });
    }

};
