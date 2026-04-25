<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        return view('backend.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'currency'          => 'required|string',
            'language'          => 'required|string',
            'delivery_charge'   => 'nullable|numeric|min:0',
            'tax_percentage'    => 'nullable|numeric|min:0',
        ]);

        $settings = Setting::first() ?? new Setting();

        $settings->currency        = $request->currency;
        $settings->language        = $request->language;
        $settings->delivery_charge = $request->delivery_charge ?? 0;
        $settings->tax_percentage  = $request->tax_percentage ?? 0;

        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
