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
        Schema::create('role_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade'); // Role ID from roles table
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade'); // Menu ID from menus table
            $table->boolean('can_create')->default(false); // Permission to create
            $table->boolean('can_edit')->default(false);   // Permission to edit
            $table->boolean('can_delete')->default(false); // Permission to delete
            $table->boolean('can_view')->default(false); // Permission to view
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
        Schema::dropIfExists('role_menus');
    }
};
