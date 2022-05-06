@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('person.breadcrumb')

            <h3>
                Person
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $person->title_prefix }} {{ $person->name }} {{ $person->title_suffix }}
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @can('update', $person)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('people.edit', $person) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('email', $person)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $person)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                @endcan
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                @can('delete', $person)
                    <form action="{{ route('people.destroy', ['person' => $person, 'redirect' => $actionRedirect ?? 'index']) }}" method="post" >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                @endcan
            </div>

        </div>
    </div>

    <div class="container my-4">
        <div class="row mt-4">
            <div class="col-lg">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                            </svg>
                            Firma
                        </div>
                    </div>
                    <div class="col-md-8">
                        @if($person->company)
                            <a href="{{ route('companies.show', $person->company) }}">{{ $person->company->full_name }}</a>
                        @else
                            nicht angegeben
                        @endif
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-5 col-md-4">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
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
                            <svg class="icon icon-16 mr-2">
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
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                            </svg>
                            Privatadresse
                        </div>
                        <p class="m-0">
                            @if ($person->address->first())
                                {{ $person->address->first()->street_number }} <br />
                                {{ $person->address->first()->postcode }} {{ $person->address->first()->city }} <br />
                                <a class="text-muted d-flex align-items-center mt-1" href="https://maps.google.com?q={{ $person->address->first()->address_line }}">
                                    <svg class="icon icon-16 mr-1">
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
                            <svg class="icon icon-16 mr-2">
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
                            <svg class="icon icon-16 mr-2">
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
                            <svg class="icon icon-16 mr-2">
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
                            <svg class="icon icon-16 mr-2">
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
                            <svg class="icon icon-16 mr-2">
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
                            <svg class="icon icon-16 mr-2">
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

        @if ($person->comment)
            <div class="text-muted d-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                </svg>
                Bemerkungen
            </div>
            {!! Html::fromMarkdown($person->comment) !!}
        @endif

    </div>
@endsection
