<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('printer_paper_size')->default('80mm')->after('logo'); // You can set the default to '80mm' or any other standard
        });
    }

    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('printer_paper_size');
        });
    }

};
