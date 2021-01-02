<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        switch ($request->tab) {
            case 'notifications':
                Auth::user()->loadCount('pushSubscriptions');

                return view('user_settings.edit_notifications');
            default:
                return redirect()->route('user-settings.edit', ['tab' => 'notifications']);
        }
    }
}
