@extends('company.show')

@section('tab')
    <div class="row">

        <div class="col-auto">
            <p class="text-muted d-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                </svg>
                Addresse
            </p>
            <p>
                @if ($company->address->first())
                    {{ $company->address->first()->street_number }} <br />
                    {{ $company->address->first()->postcode }} {{ $company->address->first()->city }} <br />
                    <a class="text-muted d-flex align-items-center mt-1" href="https://maps.google.com?q={{ $company->address->first()->address_line }}">
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
                        Tel<span class="d-none d-md-inline">efon</span>
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
                        {{ $company->phone ?? 'nicht angegeben' }}
                    </p>
                    <p>
                        {{ $company->fax ?? 'nicht angegeben' }}
                    </p>
                    <p>
                        @if ($company->email)
                            <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                        @else
                            nicht angegeben
                        @endif
                    </p>
                    <p>
                        @if ($company->website)
                            <a href="{{ $company->website }}">{{ $company->website }}</a>
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
                @if ($company->comment)
                    @markdown ($company->comment)
                @else
                    keine Bemerkungen angegeben
                @endif
            </p>
        </div>
    </div>
@endsection