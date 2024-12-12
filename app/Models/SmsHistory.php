<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsHistory extends Model
{
    use HasFactory;
    // Make these fields mass-assignable
    protected $fillable = [
        'type',          // Type of the SMS (Invoice, Promotional, etc.)
        'message',       // The content of the SMS
        'sms_parts',     // Number of parts the SMS is split into
        'sms_cost',      // The cost of sending the SMS
        'response',      // The API response (stored as JSON)
        'recipient',     // The recipient's phone number
    ];

    // Cast the 'response' attribute to an array to work with it more easily
    protected $casts = [
        'response' => 'array',
    ];
}
