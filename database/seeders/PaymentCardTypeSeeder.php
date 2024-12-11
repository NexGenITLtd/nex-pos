<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentCardType;

class PaymentCardTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cardTypes = [
            ['card_type' => 'Visa'],
            ['card_type' => 'MasterCard'],
            ['card_type' => 'American Express'],
            ['card_type' => 'Discover'],
            ['card_type' => 'Diners Club'],
            ['card_type' => 'JCB'],
        ];

        foreach ($cardTypes as $type) {
            PaymentCardType::create($type);
        }
    }
}
