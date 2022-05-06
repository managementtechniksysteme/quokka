@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('vehicle.breadcrumb')

            <h3>
                Fahrzeug bearbeiten
                <small class="text-muted">{{ $vehicle->registraation_identifier }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('vehicles.update', $vehicle) }}" method="post" novalidate>
            @method('PATCH')
            @component('vehicle.fields', [ 'vehicle' => $vehicle ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Fahrzeug speichern
            </button>
        </form>
    </div>
@endsection
