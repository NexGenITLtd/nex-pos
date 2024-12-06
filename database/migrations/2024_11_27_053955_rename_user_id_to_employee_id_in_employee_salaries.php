<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            // Add the new employee_id column
            $table->unsignedBigInteger('employee_id')->after('id'); // Adjust 'after' placement as needed

            // Copy the data from user_id to employee_id (run manually in a raw query)
        });

        // Copy the data from user_id to employee_id (manual SQL)
        DB::statement('UPDATE employee_salaries SET employee_id = user_id');

        Schema::table('employee_salaries', function (Blueprint $table) {
            // Drop the old user_id column
            $table->dropColumn('user_id');
        });
    }

    public function down()
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            // Add the user_id column back
            $table->unsignedBigInteger('user_id')->after('id'); // Adjust 'after' placement as needed
        });

        // Copy the data from employee_id to user_id (manual SQL)
        DB::statement('UPDATE employee_salaries SET user_id = employee_id');

        Schema::table('employee_salaries', function (Blueprint $table) {
            // Drop the employee_id column
            $table->dropColumn('employee_id');
        });
    }
};
