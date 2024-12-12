<?php

namespace App\Http\Controllers\Backend;

use App\Models\SmsHistory;
use App\Models\SmsSetting;
use App\Models\Store;
use App\Models\Customer;
use App\Models\UserData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view sms-history')->only('index');
        $this->middleware('permission:create sms-history')->only('create', 'store','fetchCustomers','getUserData','getBalance');
    }
    public function create()
    {
        $stores = Store::get();
        return view('sms.create', compact('stores'));
    }

    /**
     * Display a listing of all SMS histories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch SMS histories with pagination (you can adjust the number of records per page)
        $smsHistories = SmsHistory::latest()->paginate(100);

        return view('sms.index', compact('smsHistories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'message' => 'required|string|max:1600', // Assuming max message length
            'recipient' => 'required_if:all_customers,0', // Required if not sending to all customers
            'type' => 'required|string|in:generic,promotion,alert',
        ]);

        $store = Store::findOrFail($request->store_id);
        $smsSetting = SmsSetting::where('store_id', $store->id)->first();

        if (!$smsSetting) {
            return redirect()->back()->withErrors(['error' => 'SMS setting not found for the store.']);
        }

        // Fetch recipients based on 'all_customers' checkbox
        $recipients = explode(',', $request->recipient);

        // Validate that recipients exist
        if (empty($recipients)) {
            return redirect()->back()->withErrors(['error' => 'No recipients found to send SMS.']);
        }

        // Prepare message and calculate SMS parts and cost
        // $message = $request->message;
        // $smsParts = ceil(strlen($message) / 160); // 160 characters per SMS
        // $smsCostPerRecipient = $smsParts * $smsSetting->sms_rate;
        // $totalCost = $smsCostPerRecipient * count($recipients);

        $message = $request->message;

        // Check if the message contains non-Latin characters (like Bangla)
        $isUnicode = preg_match('/[^\x00-\x7F]/', $message);

        // Adjust SMS part length for Unicode messages
        $smsPartLength = $isUnicode ? 70 : 160;

        $smsParts = ceil(mb_strlen($message, 'UTF-8') / $smsPartLength);
        $smsCostPerRecipient = $smsParts * $smsSetting->sms_rate;
        $totalCost = $smsCostPerRecipient * count($recipients);


        if ($smsSetting->balance < $totalCost) {
            return redirect()->back()->withErrors(['error' => 'Insufficient balance to send SMS.']);
        }

        // Send SMS to all recipients
        $failedRecipients = [];
        foreach ($recipients as $recipient) {
            $response = $this->techno_bulk_sms(
                $smsSetting->api_url,
                $smsSetting->api_key,
                $smsSetting->sender_id,
                $recipient,
                $message,
                $smsSetting->user_email
            );

            if (!$response || (isset($response['status']) && $response['status'] !== 'success')) {
                $failedRecipients[] = $recipient;
                // Optionally log the failed attempt for further investigation
                Log::error("Failed to send SMS to $recipient: " . json_encode($response));
            }
        }

        // Update SMS settings
        $smsSetting->balance -= $totalCost;
        $smsSetting->sms_count += $smsParts * count($recipients);
        $smsSetting->save();

        // Log the SMS history
        SmsHistory::create([
            'type' => $request->type,
            'message'   => $message,
            'sms_parts' => $smsParts,
            'sms_cost'  => $totalCost,
            'response'  => json_encode($response),
            'recipient' => implode(',', $recipients),
        ]);

        // Return success message
        return redirect()->route('sms.create')->with('success', 'SMS sent successfully!');
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




    public function fetchCustomers()
    {
        $customers = Customer::whereNotNull('phone')->pluck('phone')->toArray();

        if (!empty($customers)) {
            return response()->json([
                'success' => true,
                'customers' => $customers,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No customers found.',
        ]);
    }
    public function fetchUserData()
    {
        $user_data = UserData::whereNotNull('phone')->pluck('phone')->toArray();

        if (!empty($user_data)) {
            return response()->json([
                'success' => true,
                'user_data' => $user_data,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No customers found.',
        ]);
    }
    public function getBalance($id)
    {
        $srtore_balance = SmsSetting::where('store_id', $id)->first();

        if (!$srtore_balance) {
            return response()->json(['success' => false, 'message' => 'Store not found.'], 404);
        }

        return response()->json(['success' => true, 'balance' => $srtore_balance->balance]);
    }

}
