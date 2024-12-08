<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsSetting;

class SmsSettingController extends Controller
{
    // / Display settings
    public function index()
    {
        $smsSettings  = SmsSetting::all();
        return view('sms_settings.index', compact('smsSettings'));
    }

    // Show form to create a new setting
    public function create()
    {
        return view('sms_settings.create');
    }

    // Store a new setting
    public function store(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'required|string',
            'sender_id' => 'required|string',
            'message' => 'nullable|string',
            'user_email' => 'required|email',
            'store_id' => 'nullable|integer',
            'balance' => 'required|numeric|min:0',
            'sms_rate' => 'required|numeric|min:0',
        ]);

        SmsSetting::create($validated);
        return redirect()->route('sms-settings.index')->with('success', 'SMS Setting created successfully.');
    }

    // Show edit form
    public function edit(SmsSetting $smsSetting)
    {
        return view('sms_settings.edit', compact('smsSetting'));
    }

    // Update an existing setting
    public function update(Request $request, SmsSetting $smsSetting)
    {
        $validated = $request->validate([
            'api_key' => 'required|string',
            'sender_id' => 'required|string',
            'message' => 'nullable|string',
            'user_email' => 'required|email',
            'store_id' => 'nullable|integer',
            'balance' => 'required|numeric|min:0',
            'sms_rate' => 'required|numeric|min:0',
        ]);

        $smsSetting->update($validated);
        return redirect()->route('sms-settings.index')->with('success', 'SMS Setting updated successfully.');
    }

    // Delete a setting
    public function destroy(SmsSetting $smsSetting)
    {
        $smsSetting->delete();
        return redirect()->route('sms-settings.index')->with('success', 'SMS Setting deleted successfully.');
    }
}
