@extends('layouts.app')

@section('content')
    <div class="container">

        <h3>
            Person
            <small class="text-muted">{{ $person->title_prefix }} {{ $person->name }} {{ $person->title_suffix }}</small>
        </h3>

        <a class="btn btn-primary d-inline-flex align-items-center" href="{{ route('people.edit', $person) }}">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
            </svg>
            Person bearbeiten
        </a>
        <a class="btn btn-outline-warning d-none d-lg-inline-flex align-items-center" href="#">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
            </svg>
            Person zu Favoriten hinzufügen
        </a>
        <a class="btn btn-outline-secondary d-none d-lg-inline-flex align-items-center" href="#">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
            </svg>
            Stammblatt PDF erstellen
        </a>
        <form action="{{ route('people.destroy', $person) }}" method="post" class="d-none d-lg-inline">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                </svg>
                Person entfernen
            </button>
        </form>

        <div class="dropdown d-inline d-lg-none">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownActionsButton" data-toggle="dropdown">
                Weitere Aktionen
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Person zu Favoriten hinzufügen
                </a>
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                    </svg>
                    Stammblatt PDF erstellen
                </a>
                <form action="{{ route('people.destroy', $person) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                        </svg>
                        Person entfernen
                    </button>
                </form>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                            </svg>
                            Firma
                        </div>
                    </div>
                    <div class="col-md-8">
                        {{ optional($person->company)->full_name ?? 'nicht angegeben' }}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-5 col-md-4">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                            </svg>
                            Abteilung
                        </div>
                    </div>
                    <div class="col-7 col-md-8">
                        {{ $person->department ?? 'nicht angegeben' }}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-5 col-md-4">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                            </svg>
                            Rolle
                        </div>
                    </div>
                    <div class="col-7 col-md-8">
                        {{ $person->role ?? 'nicht angegeben' }}
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                            </svg>
                            Privatadresse
                        </div>
                        <p class="m-0">
                            @if ($person->address->first())
                                {{ $person->address->first()->street_number }} <br />
                                {{ $person->address->first()->postcode }} {{ $person->address->first()->city }} <br />
                                <a class="text-muted d-flex align-items-center mt-1" href="https://maps.google.com?q={{ $person->address->first()->address_line }}">
                                    <svg class="feather feather-16 mr-1">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                    </svg>
                                    Google Maps
                                </a>
                            @else
                                keine Adresse angegeben
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg">
                <div class="row mt-4 mt-lg-0">
                    <div class="col-5 col-md-4 col-lg-5">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#phone"></use>
                            </svg>
                            Telefon geschäftlich
                        </div>
                    </div>
                    <div class="col-7 col-md-8 col-lg-7">
                        {{ $person->phone_company ?? 'nicht angegeben' }}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-5 col-md-4 col-lg-5">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#smartphone"></use>
                            </svg>
                            Telefon&nbsp;mobil
                        </div>
                    </div>
                    <div class="col-7 col-md-8 col-lg-7">
                        {{ $person->phone_mobile ?? 'nicht angegeben' }}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-5 col-md-4 col-lg-5">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#phone"></use>
                            </svg>
                            Telefon&nbsp;privat
                        </div>
                    </div>
                    <div class="col-7 col-md-8 col-lg-7">
                        {{ $person->phone_private ?? 'nicht angegeben' }}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-5  col-md-4 col-lg-5">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                            </svg>
                            Fax
                        </div>
                    </div>
                    <div class="col-7 col-md-8 col-lg-7">
                        {{ $person->fax ?? 'nicht angegeben' }}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-5 col-md-4 col-lg-5">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email
                        </div>
                    </div>
                    <div class="col-7 col-md-8 col-lg-7">
                        @if ($person->email)
                            <a href="mailto:{{ $person->email }}">{{ $person->email }}</a>
                        @else
                            nicht angegeben
                        @endif
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-5 col-md-4 col-lg-5">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#link-2"></use>
                            </svg>
                            Web<span class="d-none d-md-inline">seite</span>
                        </div>
                    </div>
                    <div class="col-7 col-md-8 col-lg-7">
                        @if ($person->website)
                            <a href="{{ $person->website }}">{{ $person->website }}</a>
                        @else
                            nicht angegeben
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="text-muted d-flex align-items-center mt-4">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Bemerkungen
        </div>
        @if ($person->comment)
            @markdown ($person->comment)
        @else
            keine Bemerkungen angegeben
        @endif

    </div>
@endsection
