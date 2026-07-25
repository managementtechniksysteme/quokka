@extends('layouts.app')

@section('content')
    <div class="container h-100 d-flex align-items-center justify-content-center">
        <div class="q-empty-state">
            <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sun"></use></svg>
            <p>Hey, du solltest eigentlich gar nicht hier sein!?<br />{{ config('app.name') }} wünscht dir einen erholsamen Urlaub.</p>
            <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('home', 'skip-holiday') }}">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use></svg>
                Übersicht anzeigen
            </a>
        </div>
    </div>
@endsection
