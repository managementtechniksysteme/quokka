@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count + action, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#bell"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Benachrichtigungen</h1>
                    @unless($notifications->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $notifications->total()) }}</div>
                    @endunless
                </div>
            </div>
            @if(Auth::user()->unreadNotifications()->count())
                <form class="d-none d-md-block" action="{{ route('notifications.clear') }}" method="post">
                    @csrf
                    <button type="submit" class="btn q-btn d-inline-flex align-items-center gap-2">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                        Alle als gelesen markieren
                    </button>
                </form>
            @endif

            {{-- Mobile: the app bar already carries "Benachrichtigungen" +
                 its own icon — collapses to just the count, inline with a
                 shortened action label (2026-07-21, user: "double headers").
                 ms-auto on the button, not justify-content-between, since
                 the count is conditional (see the same fix elsewhere in
                 Phase 2). --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                @unless($notifications->isEmpty())
                    <div class="q-subtitle mb-0">{{ trans_choice('messages.entries', $notifications->total()) }}</div>
                @endunless
                @if(Auth::user()->unreadNotifications()->count())
                    <form class="ms-auto" action="{{ route('notifications.clear') }}" method="post">
                        @csrf
                        <button type="submit" class="btn q-btn d-inline-flex align-items-center gap-2">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                            Alle gelesen
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(\App\Models\ApplicationSettings::get()->prune_read_notifications)
            <div class="q-banner mb-3">
                <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
                <span>Gelesene Nachrichten, die älter als einen Monat sind, werden automatisch aus dem System entfernt.</span>
            </div>
        @endif

        @if($notifications->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#bell"></use></svg>
                @if(request()->has('show-read'))
                    <p>Keine Benachrichtigungen vorhanden.</p>
                @else
                    <p>Keine ungelesenen Benachrichtigungen.</p>
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($notifications as $notification)
                    @include('notification.overview_card', ['notification' => $notification])
                @endforeach
            </div>
        @endif

        @if($readNotificationCount && !request()->has('show-read'))
            <div class="d-flex justify-content-center mt-4">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('notifications.index', ['show-read' => true]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                    Gelesene Benachrichtigungen anzeigen
                </a>
            </div>
        @endif

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
