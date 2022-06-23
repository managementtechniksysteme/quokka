<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:tools-scanqr');
    }

    public function index(Request $request)
    {
        $notifications = null;

        if($request->has('show-read')) {
            $readNotifications = Auth::user()
                ->notifications()
                ->whereNotNull('read_at')
                ->get();

            $notifications = Auth::user()
                ->unreadNotifications()
                ->get()
                ->concat($readNotifications)
                ->paginate(Auth::user()->settings->list_pagination_size)
                ->appends($request->except('page'));
        } else {
            $notifications = Auth::user()
                ->unreadNotifications()
                ->paginate(Auth::user()->settings->list_pagination_size);
        }

        $unreadNotificationCount = Auth::user()
            ->unreadNotifications()
            ->count();

        $readNotificationCount = Auth::user()
            ->notifications()
            ->whereNotNull('read_at')
            ->count();

        return view('notification.index')
            ->with(compact('notifications'))
            ->with(compact('unreadNotificationCount'))
            ->with(compact('readNotificationCount'));
    }

    public function destroy(DatabaseNotification $notification) {
        $notification->markAsRead();

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Die Benachrichtigung wurde erfolgreich als gelesen markiert.');
    }

    public function clear() {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Alle Benachrichtigungen wurde erfolgreich als gelesen markiert.');
    }
}
