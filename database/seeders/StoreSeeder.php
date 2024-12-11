<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stores')->insert([
            [
                'name' => 'Main Store',
                'phone' => '+8801737002123',
                'email' => 'nexgenitltd@gmail.com',
                'address' => '123 Main Street, Cityville',
                'return_policy' => 'Items can be returned within 30 days with a receipt.',
                'discount' => 10.00,
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
