@extends('layouts.app')

@section('content')
    <div class="q-container">

        @include('address.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <div class="q-avatar q-avatar--icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use></svg>
                </div>
                <div>
                    <div class="q-eyebrow">Adresse</div>
                    <h1 class="q-title">{{ $address->name }}</h1>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @can('update', $address)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('addresses.edit', $address) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="addressShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="addressShowDropdown">
                        @can('email', $address)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $address)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $address)
                            <form action="{{ route('addresses.destroy', $address) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                                    Entfernen
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="q-card mt-4" style="max-width: 400px">
            <div class="q-address__map">
                <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use></svg>
            </div>
            <div class="q-address__body">
                <div class="q-address__label">Adresse</div>
                <div class="q-address__lines">
                    {{ $address->name }}<br>
                    {{ $address->street_number }}<br>
                    {{ $address->postcode }} {{ $address->city }}
                </div>
                <a class="btn q-btn w-100 d-inline-flex align-items-center justify-content-center gap-2" href="https://maps.google.com?q={{ $address->address_line }}" target="_blank">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use></svg>
                    Google Maps öffnen
                </a>
            </div>
        </div>

    </div>
@endsection
