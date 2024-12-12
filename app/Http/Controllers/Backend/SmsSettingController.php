<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsSetting;
use App\Http\Requests\SmsSettingRequest;
use App\Models\Store;

class SmsSettingController extends Controller
{
    // / Display settings
    public function index()
    {
        $smsSettings  = SmsSetting::all();
        return view('sms.settings.index', compact('smsSettings'));
    }

    // Show form to create a new setting
    public function create()
    {
        $stores = Store::get();
        return view('sms.settings.create', compact('stores'));
    }

    // Store a new setting
    public function store(SmsSettingRequest $request)
    {
        SmsSetting::create($request->validated());
        return redirect()->route('sms-settings.index')->with('success', 'SMS Setting created successfully.');
    }

    // Show edit form
    public function edit(SmsSetting $smsSetting)
    {
        $stores = Store::get();
        return view('sms.settings.edit', compact('smsSetting','stores'));
    }

    // Update an existing setting
    public function update(SmsSettingRequest $request, $id)
    {
        $smsSetting = SmsSetting::findOrFail($id);

        $smsSetting->update($request->validated());

        return redirect()->back()->with('success', 'SMS settings updated successfully.');
    }

    // Delete a setting
    public function destroy(SmsSetting $smsSetting)
    {
        $smsSetting->delete();
        return redirect()->route('sms-settings.index')->with('success', 'SMS Setting deleted successfully.');
    }
}
