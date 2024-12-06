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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'job_title')) {
                $table->dropColumn('job_title');
            }
            if (Schema::hasColumn('users', 'date_of_birth')) {
                $table->dropColumn('date_of_birth');
            }
            if (Schema::hasColumn('users', 'join_date')) {
                $table->dropColumn('join_date');
            }
            if (Schema::hasColumn('users', 'salary')) {
                $table->dropColumn('salary');
            }
            if (Schema::hasColumn('users', 'nid')) {
                $table->dropColumn('nid');
            }
            if (Schema::hasColumn('users', 'blood_group')) {
                $table->dropColumn('blood_group');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('job_title')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('join_date')->nullable();
            $table->float('salary')->default(0);
            $table->string('nid', 40)->nullable();
            
            $table->string('blood_group', 10)->nullable();
        });
    }
};
