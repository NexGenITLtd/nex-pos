<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rack;

class RackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $racks = [
            [
                'name' => 'Rack A',
                'store_id' => '1',
            ],
            [
                'name' => 'Rack B',
                'store_id' => '1',
            ],
            [
                'name' => 'Rack C',
                'store_id' => '1',
            ],
            [
                'name' => 'Rack D',
                'store_id' => '1',
            ],
        ];

        foreach ($racks as $rack) {
            Rack::create($rack);
        }
    }
}
