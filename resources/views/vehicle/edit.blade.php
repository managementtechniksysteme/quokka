@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('vehicle.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#truck"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Fahrzeug bearbeiten</div>
                    <h1 class="q-title">{{ $vehicle->registration_identifier }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('vehicles.update', $vehicle) }}" method="post" novalidate>
            @method('PATCH')
            @include('vehicle.fields', ['vehicle' => $vehicle])

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('vehicles.show', $vehicle) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Fahrzeug speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
