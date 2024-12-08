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
            'api_key' => '181785150221847320241208121128pmGpW9kUMn',
            'sender_id' => '411',
            'user_email' => 'ashikurashik.sc@gmail.com',
            'store_id' => 1,
            'balance' => 100.00,
            'sms_rate' => 0.40,
            'sms_count' => 0,
            'api_url'    => 'https://24bulksms.com/24bulksms/api/api-sms-send',
        ]);
    }
}
