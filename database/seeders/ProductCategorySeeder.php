<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        // Create root categories
        $rootCategories = [
            ['name' => 'Electronics', 'parent_id' => null],
            ['name' => 'Fashion', 'parent_id' => null],
            ['name' => 'Home Appliances', 'parent_id' => null],
        ];

        foreach ($rootCategories as $root) {
            ProductCategory::create($root);
        }

        // Create subcategories
        $childCategories = [
            ['name' => 'Mobile Phones', 'parent_id' => 1], // Assuming 'Electronics' has ID 1
            ['name' => 'Laptops', 'parent_id' => 1],      // Assuming 'Electronics' has ID 1
            ['name' => 'Clothing', 'parent_id' => 2],     // Assuming 'Fashion' has ID 2
            ['name' => 'Shoes', 'parent_id' => 2],        // Assuming 'Fashion' has ID 2
            ['name' => 'Refrigerators', 'parent_id' => 3],// Assuming 'Home Appliances' has ID 3
            ['name' => 'Microwaves', 'parent_id' => 3],   // Assuming 'Home Appliances' has ID 3
        ];

        foreach ($childCategories as $child) {
            ProductCategory::create($child);
        }
    }
}
