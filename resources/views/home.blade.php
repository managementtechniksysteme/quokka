@extends('layouts.app')

@section('content')
    <webpush-manager v-cloak></webpush-manager>
    @if(Auth::user()->push_subscriptions_count > 0)
        <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('webpush.test') }}">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
            </svg>
            Test Benachrichtigung senden
        </a>
    @endif
@endsection
