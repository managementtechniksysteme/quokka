<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:tools-scanqr');
    }

    public function index()
    {
        $notifications = Auth::user()
            ->unreadNotifications()
            ->paginate(Auth::user()->settings->list_pagination_siz);

        return view('notification.index')
            ->with(compact('notifications'));
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
