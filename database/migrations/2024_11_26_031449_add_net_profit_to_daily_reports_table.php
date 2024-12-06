<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->decimal('net_profit', 15, 2)->after('total_profit')->nullable()->comment('Net profit after deductions');
        });
    }

    public function down()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropColumn('net_profit');
        });
    }
};
