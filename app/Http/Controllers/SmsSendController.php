<?php
// app/Http/Controllers/SmsSendController.php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SmsSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsSendController extends Controller
{
    public function send(Request $request, Invoice $invoice)
    {
        if ($request->has('send_sms') && $request->send_sms) {
            // Retrieve the SMS setting for the store
            $smsSetting = SmsSetting::where('store_id', $invoice->store_id)->first();

            if (!$smsSetting) {
                return response()->json(['error' => 'SMS setting not found for the store.'], 404);
            }

            // Construct the SMS message
            $message = "Invoice #{$invoice->id}\n"
                     . "Total: {$invoice->total_bill}\n"
                     . "Paid: {$invoice->paid_amount}\n"
                     . "Due: {$invoice->due_amount}\n"
                     . "Thank you for your purchase!";

            // Calculate SMS parts and cost
            $smsParts = ceil(strlen($message) / 160); // 160 characters per SMS
            $smsCost = $smsParts * $smsSetting->sms_rate;

            // Check if balance is sufficient
            if ($smsSetting->balance < $smsCost) {
                return response()->json(['error' => 'Insufficient balance to send SMS.'], 400);
            }

            // Send SMS via API
            $response = Http::post($smsSetting->api_url, [
                'api_key'    => $smsSetting->api_key,
                'sender_id'  => $smsSetting->sender_id,
                'to'         => $invoice->customer->phone, // Assuming the customer model has a phone number
                'message'    => $message,
            ]);

            // Handle the response
            if ($response->successful()) {
                // Deduct balance and increment SMS count
                $smsSetting->balance -= $smsCost;
                $smsSetting->sms_count += $smsParts;
                $smsSetting->save();

                Log::info('SMS sent successfully.', [
                    'message' => $message,
                    'cost' => $smsCost,
                    'parts' => $smsParts,
                    'response' => $response->body(),
                ]);

                return response()->json(['success' => 'SMS sent successfully!']);
            }

            return response()->json(['error' => 'Failed to send SMS.'], $response->status());
        }

        return response()->json(['message' => 'SMS not sent (checkbox not checked).']);
    }
}

