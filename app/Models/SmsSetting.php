<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'api_key', 'sender_id', 'message', 'user_email',
        'balance', 'sms_rate', 'sms_count', 'api_url',
    ];

    /**
     * Calculate SMS parts based on the message length.
     */
    public function calculateSmsParts(string $message): int
    {
        return ceil(strlen($message) / 160); // 160 characters per SMS
    }

    /**
     * Calculate the cost of sending an SMS.
     */
    public function calculateSmsCost(string $message): float
    {
        $parts = $this->calculateSmsParts($message);
        return $parts * $this->sms_rate;
    }
}
