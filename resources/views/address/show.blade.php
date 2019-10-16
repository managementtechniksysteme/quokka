@extends('layouts.app')

@section('content')
    <div class="container">

        <h3>
            Addresse
            <small class="text-muted">{{ $address->address_line }}</small>
        </h3>

        <a class="btn btn-primary d-inline-flex align-items-center" href="{{ route('addresses.edit', $address) }}">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
            </svg>
            Adresse bearbeiten
        </a>
        <a class="btn btn-outline-warning d-none d-lg-inline-flex align-items-center" href="#">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
            </svg>
            Adresse zu Favoriten hinzufügen
        </a>
        <form action="{{ route('addresses.destroy', $address) }}" method="post" class="d-none d-lg-inline">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                </svg>
                Adresse entfernen
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
                    Adresse zu Favoriten hinzufügen
                </a>
                <form action="{{ route('addresses.destroy', $address) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                        </svg>
                        Adresse entfernen
                    </button>
                </form>
            </div>
        </div>

        <div class="row mt-4">

            <div class="col">

                <p class="text-muted d-flex align-items-center">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                    </svg>
                    Adresse
                </p>
                <p>
                    {{ $address->street_number }} <br />
                    {{ $address->postcode }} {{ $address->city }} <br />
                    <a class="text-muted d-flex align-items-center mt-1" href="https://maps.google.com?q={{ $address->address_line }}">
                        <svg class="feather feather-16 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                        </svg>
                        Google Maps
                    </a>
                </p>
            </div>

        </div>

    </div>
@endsection
