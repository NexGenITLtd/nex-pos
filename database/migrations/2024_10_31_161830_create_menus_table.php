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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('route')->nullable(); // Route assigned to the menu
            $table->string('icon')->nullable(); // FontAwesome or other icons
            $table->unsignedBigInteger('parent_id')->nullable(); // For nested menus
            $table->integer('order')->default(0); // For sorting menus
            $table->timestamps();
            
            // Foreign key constraint for parent_id to reference the id of the same table (self-referencing)
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
            
            // Index for better performance on parent_id and order
            $table->index('parent_id');
            $table->index('order');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
};
