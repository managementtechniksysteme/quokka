@extends('user_settings.edit')

@section('tab')
    <div class="row">
        <div class="col">
            <p class="text-muted d-inline-flex align-items-center mb-1">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#smartphone"></use>
                </svg>
                Push Benachrichtigungen
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="alert alert-info mt-1" role="alert">
                <div class="d-inline-flex align-items-center">
                    <svg class="feather feather-24 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                    </svg>
                    Push Benachrichtigungen müssen aus technschen Gründen auf jedem Gerät separat aktiviert beziehungsweise
                    deaktiviert werden.
                </div>
            </div>
        </div>

    </div>


    <div class="row mt-2">
        <div class="col">
            <webpush-manager v-cloak></webpush-manager>
        </div>
    </div>

    @if(Auth::user()->push_subscriptions_count > 0)

        <div class="row mt-4">
            <div class="col">
                <p>
                    Push Benachrichtigungen testen. Es wird eine Test Benachrichtigung an
                    {{ trans_choice('messages.devices', Auth::user()->push_subscriptions_count, ['number' => Auth::user()->push_subscriptions_count]) }}
                    gesendet.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('webpush.test') }}">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                    </svg>
                    Test Benachrichtigung senden
                </a>
            </div>
        </div>

    @endif

@endsection
