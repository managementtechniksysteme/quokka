@extends('layouts.app')

@php use \App\Models\Person; @endphp
@php use \App\Models\Service; @endphp

@if (old('employee_ids'))
    @php $currentEmployees = Person::find(old('employee_ids')); @endphp
@endif

@if (old('service_ids'))
    @php $currentServices = Service::find(old('service_ids')); @endphp
@endif

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('project.breadcrumb')

            <h3>
                Projektauswertung als PDF erstellen
                <small class="text-muted">{{ $project->name }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('projects.download', $project) }}" method="post" novalidate>
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <p class="d-inline-flex align-items-center mb-1">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                        </svg>
                        Zeitraum
                    </p>
                    <p class="text-muted">
                        Der Zeitraum der Auswertung.
                    </p>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label for="start">Start</label>
                        <input type="date" class="form-control @error('start') is-invalid @enderror" id="start" name="start" placeholder="" value="{{ old('start', $project->starts_on?->format('Y-m-d')) ?? '' }}" />
                        <div class="invalid-feedback">
                            @error('start')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="end">Ende</label>
                        <input type="date" class="form-control @error('end') is-invalid @enderror" id="end" name="end" placeholder="" value="{{ old('end') }}" />
                        <div class="invalid-feedback">
                            @error('end')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <p class="d-inline-flex align-items-center mb-1">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                        </svg>
                        Mitarbeiter
                    </p>
                    <p class="text-muted">
                        Die Mitarbeiter, welche in der Auswertung vorhanden sein sollen.
                    </p>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label for="employee_ids">Mitarbeiter</label>
                        <people-selector inputname="employee_ids[]" :people="{{ $employees }}" :current_people="{{ $currentEmployees ?? 'null' }}" v-cloak></people-selector>
                        <div class="invalid-feedback @error('employee_ids') d-block @enderror">
                            @error('employee_ids')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <p class="d-inline-flex align-items-center mb-1">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                        </svg>
                        Leistungen
                    </p>
                    <p class="text-muted">
                        Die Leistungen, welche in der Auswertung vorhanden sein sollen.
                    </p>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label for="service_ids">Leistungen</label>
                        <accounting-services-selector inputname="service_ids[]" :services="{{ $services }}" :current_services="{{ $currentServices ?? 'null' }}" v-cloak></accounting-services-selector>
                        <div class="invalid-feedback @error('service_ids') d-block @enderror">
                            @error('service_ids')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                </svg>
                PDF erstellen
            </button>
        </form>
    </div>
@endsection
