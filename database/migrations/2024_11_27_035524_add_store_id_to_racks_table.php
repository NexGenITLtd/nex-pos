<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('racks', function (Blueprint $table) {
            $table->unsignedBigInteger('store_id')->nullable();  // Add store_id column
        });
    }

    public function down()
    {
        Schema::table('racks', function (Blueprint $table) {
            $table->dropColumn('store_id');  // Remove store_id column if migration is rolled back
        });
    }
};
