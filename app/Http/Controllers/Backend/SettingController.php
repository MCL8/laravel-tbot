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
        Settings::where('key', '!=', null)->delete();

        foreach ($request->except('_token') as $key => $value) {
            $setting = new Settings;
            $setting->key = $key;
            $setting->value = $request->$key;
            $setting->save();
        }

        return redirect()->route('admin.setting.index');
    }

    public function setWebhook(Request $request)
    {
        $result = $this->sendTelegramData('setwebhook', [
            'query' => ['url' => $request->url . '/' . \Telegram::getAccessToken()]
        ]);

        return redirect()->route('admin.setting.index')->with('status', $result);
    }

    public function getWebhookInfo(Request $request)
    {
        $result = $this->sendTelegramData('getWebhookInfo');

        return redirect()->route('admin.setting.index')->with('status', $result);
    }

    public function sendTelegramData($route = '', $params = [], $method = 'POST')
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.telegram.org/bot' . \Telegram::getAccessToken() . '/']);
        $result = $client->request($method, $route, $params);

        return (string)$result->getBody();
    }
}
