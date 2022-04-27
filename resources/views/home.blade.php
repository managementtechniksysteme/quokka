@extends('layouts.app')

@section('content')
    <div class="container-fluid h-100 d-flex flex-column align-items-center justify-content-center">

        <div class="text-center pt-4">
            <img class="empty-state" src="{{ asset('svg/dashboard.svg') }}" alt="relax" />
            <p class="lead text-muted mt-4">Hier entsteht in Kürze deine persönliche Übersicht.<br />
                Bitte gedulde dich noch ein wenig.</p>

            <p class="lead">Du kannst einstweilen andere Bereiche von {{ config('app.name') }} erkunden.</p>

        </div>

        <div class="row pb-4">
            <div class="col col-sm-6 col-md-4 col-xl-auto mt-2">
                <a class="btn btn-primary btn-lg btn-block d-inline-flex align-items-center justify-content-center" href="{{ route('companies.index') }}">
                    <svg class="feather feather-20 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                    </svg>
                    Firmen anzeigen
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-auto  mt-2">
                <a class="btn btn-primary btn-lg btn-block d-inline-flex align-items-center justify-content-center" href="{{ route('projects.index') }}">
                    <svg class="feather feather-20 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                    </svg>
                    Projekte anzeigen
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-auto  mt-2">
                <a class="btn btn-primary btn-lg btn-block d-inline-flex align-items-center justify-content-center" href="{{ route('tasks.index') }}">
                    <svg class="feather feather-20 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                    </svg>
                    Aufgaben anzeigen
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-auto  mt-2">
                <a class="btn btn-primary btn-lg btn-block d-inline-flex align-items-center justify-content-center" href="{{ route('accounting.index') }}">
                    <svg class="feather feather-20 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                    </svg>
                    Zur Abrechnung
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-auto  mt-2">
                <a class="btn btn-primary btn-lg btn-block d-inline-flex align-items-center justify-content-center" href="{{ route('logbook.index') }}">
                    <svg class="feather feather-20 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#book"></use>
                    </svg>
                    Zum Fahrtenbuch
                </a>
            </div>
        </div>

    </div>
@endsection
