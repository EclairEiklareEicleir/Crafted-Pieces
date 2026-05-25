<?php

namespace App\Http\Controllers;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index', [
            'settings' => Setting::all()->groupBy('key')
        ]);
    }

    public function update(Request $request)
    {
        foreach ($request->settings as $key => $value) {

            Setting::where('key', $key)->update([
                'value' => $value
            ]);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
