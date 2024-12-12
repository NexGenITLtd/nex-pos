<?php
// app/Http/Controllers/SmsSendController.php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SmsSetting;
use App\Models\SmsHistory;
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
            $message = "{$invoice->store->name}\n"
                     . "Invoice #{$invoice->id}\n"
                     . "Total: {$invoice->total_bill}\n"
                     . "Paid: {$invoice->paid_amount}\n"
                     . ($invoice->due_amount > 0 ? "Due: {$invoice->due_amount}\n" : "")
                     . "Thank you for your purchase!";

            // Calculate SMS parts and cost
            $smsParts = ceil(strlen($message) / 160); // 160 characters per SMS
            $smsCost = $smsParts * $smsSetting->sms_rate;

            // Check if balance is sufficient
            if ($smsSetting->balance < $smsCost) {
                return response()->json(['message' => 'Insufficient balance to send SMS.'], 400);
            }

            $response = $this->techno_bulk_sms(
                $smsSetting->api_url, 
                $smsSetting->api_key, 
                $smsSetting->sender_id, 
                $invoice->customer->phone, 
                $message, 
                $smsSetting->user_email
            );

            // Handle the response as an array
            if ($response && isset($response['message']) && $response['message'] == 'Data Missing') {
                return response()->json(['message' => 'Data Missing'], 400);
            }

            if ($response && isset($response['status']) && $response['status'] == 'success') {
                // Deduct balance and increment SMS count
                $smsSetting->balance -= $smsCost;
                $smsSetting->sms_count += $smsParts;
                $smsSetting->save();

                // Log the SMS history
                SmsHistory::create([
                    'type'      => 'Invoice',
                    'message'   => $message,
                    'sms_parts' => $smsParts,
                    'sms_cost'  => $smsCost,
                    'response'  => json_encode($response),
                    'recipient' => $invoice->customer->phone,
                ]);

                return response()->json(['success' => 'SMS sent successfully!']);
            } else {
                $errorMessage = $response ? json_encode($response) : '{"message":"No response"}';

                // Log the SMS history even if it failed
                SmsHistory::create([
                    'type'      => 'Invoice',
                    'message'   => $message,
                    'sms_parts' => $smsParts,
                    'sms_cost'  => $smsCost,
                    'response'  => $errorMessage,
                    'recipient' => $invoice->customer->phone,
                ]);

                return response()->json(['error' => $errorMessage], 500);
            }
        }
    }

    function techno_bulk_sms($api_url, $api_key, $sender_id, $mobile_no, $message, $user_email)
    {
        $data = [
            'api_key' => $api_key,
            'sender_id' => $sender_id,
            'message' => $message,
            'mobile_no' => $mobile_no,
            'user_email' => $user_email
        ];

        $curl = curl_init($api_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $output = curl_exec($curl);
        curl_close($curl);

        return json_decode($output, true);
    }
}



