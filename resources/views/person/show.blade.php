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

            <div class="col-auto">
                <div class="row">
                    <div class="col-auto">
                        <p class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                            </svg>
                            Firma
                        </p>
                        <p class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                            </svg>
                            Abteilung
                        </p>
                        <p class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                            </svg>
                            Rolle
                        </p>
                    </div>

                    <div class="col-auto">
                        <p>
                            {{ optional($person->company)->full_name ?? 'nicht angegeben' }}
                        </p>
                        <p>
                            {{ $person->department ?? 'nicht angegeben' }}
                        </p>
                        <p>
                            {{ $person->role ?? 'nicht angegeben' }}
                        </p>
                    </div>
                </div>

                <p class="text-muted d-flex align-items-center mb-1">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                    </svg>
                    Privatadresse
                </p>
                <p>
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

            <div class="col-auto">

                <div class="row">

                    <div class="col-auto">
                        <p class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#phone"></use>
                            </svg>
                            Tel<span class="d-none d-md-inline">efon</span>&nbsp;g<span class="d-none d-md-inline">eschäftlich</span>
                        </p>
                        <p class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#smartphone"></use>
                            </svg>
                            Tel<span class="d-none d-md-inline">efon</span>&nbsp;m<span class="d-none d-md-inline">obil</span>
                        </p>
                        <p class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#phone"></use>
                            </svg>
                            Tel<span class="d-none d-md-inline">efon</span>&nbsp;p<span class="d-none d-md-inline">rivat</span>
                        </p>
                        <p class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                            </svg>
                            Fax
                        </p>
                        <p class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email
                        </p>
                        <p class="text-muted d-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#link-2"></use>
                            </svg>
                            Web<span class="d-none d-md-inline">seite</span>
                        </p>
                    </div>

                    <div class="col-auto">
                        <p>
                            {{ $person->phone_company ?? 'nicht angegeben' }}
                        </p>
                        <p>
                            {{ $person->phone_mobile ?? 'nicht angegeben' }}
                        </p>
                        <p>
                            {{ $person->phone_private ?? 'nicht angegeben' }}
                        </p>
                        <p>
                            {{ $person->fax ?? 'nicht angegeben' }}
                        </p>
                        <p>
                            @if ($person->email)
                                <a href="mailto:{{ $person->email }}">{{ $person->email }}</a>
                            @else
                                nicht angegeben
                            @endif
                        </p>
                        <p>
                            @if ($person->website)
                                <a href="{{ $person->website }}">{{ $person->website }}</a>
                            @else
                                nicht angegeben
                            @endif
                        </p>
                    </div>

                </div>

            </div>

        </div>

        <div class="row mt-2">
            <div class="col">
                <p class="text-muted d-flex align-items-center">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                    </svg>
                    Bemerkungen
                </p>
                <p>
                    @if ($person->comment)
                        @markdown ($person->comment)
                    @else
                        keine Bemerkungen angegeben
                    @endif
                </p>
            </div>
        </div>

    </div>
@endsection
