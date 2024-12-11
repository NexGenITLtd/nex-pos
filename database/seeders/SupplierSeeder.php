<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = [
            [
                'name' => 'ABC Supplies',
                'contact_person' => 'John Doe',
                'phone' => '1234567890',
                'email' => 'abc@example.com',
                'address' => '123 Main Street, Cityville',
                'img' => null,
            ],
            [
                'name' => 'XYZ Wholesalers',
                'contact_person' => 'Jane Smith',
                'phone' => '0987654321',
                'email' => 'xyz@example.com',
                'address' => '456 Elm Street, Townsville',
                'img' => null,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
