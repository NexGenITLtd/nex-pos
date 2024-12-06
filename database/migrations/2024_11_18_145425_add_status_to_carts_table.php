<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('status')->nullable()->after('vat'); // Adjust 'after' as per your preference
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
