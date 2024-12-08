<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmsSetting;

class SmsSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        SmsSetting::create([
            'api_key' => 'example_api_key',
            'sender_id' => 'example_sender',
            'user_email' => 'admin@example.com',
            'store_id' => 1,
            'balance' => 100.00,
            'sms_rate' => 0.5,
            'sms_count' => 0,
            'api_url'    => 'https://24bulksms.com/24bulksms/api/api-sms-send',
        ]);
    }
}
