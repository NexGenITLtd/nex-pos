<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankAccount;
use App\Models\Store;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get some store IDs from the stores table
        $storeIds = Store::pluck('id')->toArray();

        // Seed data for bank accounts
        $bankAccounts = [
            [
                'bank_name' => 'Cash',
                'account_no' => '1',
                'account_type' => 'cash',
                'initial_balance' => 0,
                'store_id' => $storeIds[array_rand($storeIds)], // Randomly assign a store
            ],
            [
                'bank_name' => 'Card',
                'account_no' => 'xxx',
                'account_type' => 'card',
                'initial_balance' => 0,
                'store_id' => $storeIds[array_rand($storeIds)], // Randomly assign a store
            ],
            [
                'bank_name' => 'Bkash',
                'account_no' => 'xxxxx',
                'account_type' => 'mobile',
                'initial_balance' => 0,
                'store_id' => $storeIds[array_rand($storeIds)], // Randomly assign a store
            ],
        ];

        foreach ($bankAccounts as $account) {
            BankAccount::create($account);
        }
    }
}
