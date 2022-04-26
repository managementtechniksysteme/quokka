@extends('layouts.app')

@section('content')
    <div class="container h-100 d-flex align-items-center justify-content-center">
        <div class="text-center">
            <img class="empty-state" src="{{ asset('svg/dashboard.svg') }}" alt="relax" />
            <p class="lead text-muted mt-4">Hier entsteht in Kürze deine persönliche Übersicht.<br />
                Bitte gedulde dich noch ein wenig.</p>

            <p class="lead">Du kannst einstweilen andere Bereiche von {{ config('app.name') }} erkunden.</p>
            <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('companies.index') }}">
                <svg class="feather feather-20 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                </svg>
                Firmen anzeigen
            </a>
            <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('projects.index') }}">
                <svg class="feather feather-20 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                </svg>
                Projekte anzeigen
            </a>
            <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('tasks.index') }}">
                <svg class="feather feather-20 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                </svg>
                Aufgaben anzeigen
            </a>
            <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('accounting.index') }}">
                <svg class="feather feather-20 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                </svg>
                Zur Abrechnung
            </a>
            <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('logbook.index') }}">
                <svg class="feather feather-20 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#book"></use>
                </svg>
                Zum Fahrtenbuch
            </a>
        </div>
    </div>
@endsection
