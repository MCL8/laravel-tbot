<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Settings;

class SettingController extends Controller
{
    public function index()
    {
        return view('backend.setting', Settings::getSettings());
    }

    public function store(Request $request)
    {
        Settings::where('key', '!=', NULL)->delete();

        foreach ($request->except('_token') as $key => $value) {
            $setting = new Settings;
            $setting->key = $key;
            $setting->value = $request->$key;
            $setting->save();
        }

        return redirect()->route('admin.setting.index');
    }
}
