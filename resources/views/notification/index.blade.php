@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container @if(Auth::user()->unreadNotifications()->count()) pt-4 @else py-4 @endif">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#bell"></use>
                </svg>
                Benachrichtigungen
                @unless($notifications->isEmpty())
                    <small class="text-muted">{{ trans_choice('messages.entries', $notifications->total()) }}</small>
                @endif
            </h3>

            @if(Auth::user()->unreadNotifications()->count())
                <div class="scroll-x d-flex">
                    <form action="{{ route('notifications.clear') }}" method="post" >
                        @csrf

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            Alle als gelesen markieren
                        </button>
                    </form>
                </div>
            @endif
        </div>

    </div>

    <div class="container my-4">
        <div class="mt-3">
            @forelse ($notifications as $notification)
                @component('notification.overview_card', [ 'notification' => $notification, ])
                @endcomponent

                @if(!$loop->last)
                    <hr class="m-0 mx-1"/>
                @endif
            @empty
                <div class="text-center mt-4">
                    <img class="empty-state" src="{{ asset('svg/astronaut.svg') }}" alt="no data"/>
                    @if( request()->has('show-read') )
                        <p class="lead text-muted">Du hast keine Benachrichtigungen.</p>
                    @else
                        <p class="lead text-muted">Du hast keine ungelesenen Benachrichtigungen.</p>
                    @endif
                </div>
            @endforelse
        </div>

        @if( $readNotificationCount && !request()->has('show-read') )
            <div class="d-flex justify-content-center mt-4">
                <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('notifications.index', ['show-read' => true]) }}">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                    </svg>
                    Gelesene Benachrichtigungen anzeigen
                </a>
            </div>
        @endif

        <div class="mt-2">
            {{ $notifications->links() }}
        </div>

    </div>
@endsection
