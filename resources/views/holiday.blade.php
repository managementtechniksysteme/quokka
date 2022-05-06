@extends('layouts.app')

@section('content')
    <div class="container h-100 d-flex align-items-center justify-content-center">
        <div class="text-center">
            <img class="empty-state" src="{{ asset('svg/chilling.svg') }}" alt="relax" />
            <p class="lead mt-4">Hey, du solltest eigentlich gar nicht hier sein!?<br />
            {{ config('app.name') }} wünscht dir einen erholsamen Urlaub.</p>

            <p class="lead text-muted">Du kannst trotz Urlaubs in deine Übersicht.</p>
            <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('home', 'skip-holiday') }}">
                <svg class="icon icon-20 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#activity"></use>
                </svg>
                Übersicht anzeigen
            </a>
        </div>
    </div>
@endsection
