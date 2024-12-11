<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            ['name' => 'Kilogram'],
            ['name' => 'Gram'],
            ['name' => 'Liter'],
            ['name' => 'Milliliter'],
            ['name' => 'Piece'],
            ['name' => 'Box'],
            ['name' => 'Packet'],
            ['name' => 'Dozen'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
