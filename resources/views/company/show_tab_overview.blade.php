@extends('company.show')

@section('tab')

    <div class="row">
        <div class="col-md-4">
            <div class="text-muted d-flex align-items-center">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                </svg>
                Addresse
            </div>
            <p class="m-0">
                @if ($company->address->first())
                    {{ $company->address->first()->street_number }} <br />
                    {{ $company->address->first()->postcode }} {{ $company->address->first()->city }} <br />
                    <a class="text-muted d-flex align-items-center mt-1" href="https://maps.google.com?q={{ $company->address->first()->address_line }}">
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

        <div class="col-md-8">
            <div class="row mt-4 mt-md-0">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#phone"></use>
                        </svg>
                        Telefon
                    </div>
                </div>
                <div class="col">
                    {{ $company->phone ?? 'nicht angegeben' }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        Fax
                    </div>
                </div>
                <div class="col">
                    {{ $company->fax ?? 'nicht angegeben' }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email
                    </div>
                </div>
                <div class="col">
                    @if ($company->email)
                        <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                    @else
                        nicht angegeben
                    @endif
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#link-2"></use>
                        </svg>
                        Webseite
                    </div>
                </div>
                <div class="col">
                    @if ($company->website)
                        <a href="{{ $company->website }}">{{ $company->website }}</a>
                    @else
                        nicht angegeben
                    @endif
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                        </svg>
                        Ansprechperson
                    </div>
                </div>
                <div class="col">
                    @if ($company->contactPerson)
                        <a href="{{ route('people.show', $company->contactPerson) }}">{{ $company->contactPerson->name }}</a>
                    @else
                        nicht angegeben
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($company->comment)
        <div class="text-muted d-flex align-items-center mt-4">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Bemerkungen
        </div>
        {!! Html::fromMarkdown($company->comment) !!}
    @endif

@endsection
