<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
    Schema::create('product_categories', function (Blueprint $table) {
        $table->id();
        $table->string('name', 150)->nullable();
        // Set 'parent_id' to nullable, default to 0, and create foreign key constraint
        $table->foreignId('parent_id')
              ->nullable()  // Allows NULL for root categories
              ->default(0)  // Default value for root categories
              ->constrained('product_categories')  // Foreign key referencing the same table
              ->onDelete('cascade');  // Cascade delete for child categories if parent is deleted
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
    Schema::dropIfExists('product_categories');
    }

}
