<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiUrlToSmsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_settings', function (Blueprint $table) {
            $table->string('api_url')->nullable()->after('sms_count'); // Add 'api_url' after 'sms_count'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_settings', function (Blueprint $table) {
            $table->dropColumn('api_url'); // Remove the 'api_url' column if migration is rolled back
        });
    }
}
