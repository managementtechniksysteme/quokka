<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebpushDestroyRequest;
use App\Http\Requests\WebpushStoreRequest;
use App\Notifications\WebpushTestNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class WebpushController extends Controller
{
    public function store(WebpushStoreRequest $request)
    {
        Auth::user()->updatePushSubscription($request->endpoint, $request->public_key, $request->auth_token, $request->content_encoding);

        return response()->json(['success' => true], Response::HTTP_OK);
    }

    public function destroy(WebpushDestroyRequest $request)
    {
        Auth::user()->deletePushSubscription($request->endpoint);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function test(Request $request)
    {
        Notification::send(Auth::user(), new WebpushTestNotification());

        return redirect()->route('user-settings.edit', ['tab' => 'notifications'])->with('success', 'Die Test Benachrichtigung wurde erfolgreich gesendet');
    }
}
