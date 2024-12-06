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
    Schema::dropIfExists('employees'); // Drop the table if it already exists

    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->foreignId('store_id')->nullable()->default(0)->constrained()->onDelete('set null');
        $table->string('name')->nullable();
        $table->string('phone')->nullable();
        $table->string('email')->nullable()->unique();
        $table->string('role', 50)->nullable();
        $table->string('job_title')->nullable();
        $table->date('date_of_birth')->nullable();
        $table->date('join_date')->nullable();
        $table->double('salary', 8, 2)->default(0);
        $table->string('nid', 40)->nullable();
        $table->string('blood_group', 10)->nullable();
        $table->string('image')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
