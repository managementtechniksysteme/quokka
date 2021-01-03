<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSettingsUpdateGeneralRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingsController extends Controller
{
    public function edit(Request $request)
    {
        switch ($request->tab) {
            case 'general':
                return view('user_settings.edit_general');
            case 'notifications':
                Auth::user()->loadCount('pushSubscriptions');

                return view('user_settings.edit_notifications');
            default:
                return redirect()->route('user-settings.edit', ['tab' => 'general']);
        }
    }

    public function updateSignature(UserSettingsUpdateGeneralRequest $request)
    {
        $validatedData = $request->validated();

        Auth::user()->addSignature($request->signature);

        return redirect()->route('user-settings.edit', ['tab' => 'general'])->with('success', 'Die Unterschrift wurde erfolgreich gepseichert.');
    }
}
