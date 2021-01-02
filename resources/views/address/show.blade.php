@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('address.breadcrumb')

            <h3>
                Addresse
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $address->name }}, {{ $address->address_line }}
                    <svg class="feather feather-16 text-yellow ml-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                </small>
            </h3>

            <div class="scroll-x d-flex">
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('addresses.edit', $address) }}">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                    </svg>
                    Bearbeiten
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                    </svg>
                    Email versenden
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                    </svg>
                    PDF erstellen
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                <form action="{{ route('addresses.destroy', $address) }}" method="post" >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                        </svg>
                        Entfernen
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="text-muted d-flex align-items-center mt-4">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
            </svg>
            Adresse
        </div>
        <p>
            {{ $address->name}} <br />
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
@endsection
