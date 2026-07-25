@extends('layouts.app')

@php use \App\Models\Person; @endphp
@php use \App\Models\Service; @endphp

@if (old('employee_ids'))
    @php $currentEmployees = Person::find(old('employee_ids')); @endphp
@endif

@if (old('service_ids'))
    @php $currentServices = Service::find(old('service_ids')); @endphp
@endif

@section('mobile-detail-bar')
    <a href="{{ route('projects.show', $project) }}" class="q-appbar__btn" aria-label="Zurück zum Projekt">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title">Projektauswertung als PDF</span>
@endsection

@section('content')
    <div class="q-container q-container--narrow">
        <div class="d-none d-md-block">
            @include('project.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">{{ $project->name }}</div>
                    <h1 class="q-title">Projektauswertung als PDF erstellen</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation mt-2 mt-md-0" action="{{ route('projects.download', $project) }}" method="post" novalidate>
            @csrf

            <div class="q-form-section">
                <div class="q-form-section__head">
                    Zeitraum
                    <div class="q-form-section__desc">Der Zeitraum der Auswertung.</div>
                </div>
                <div class="q-form-section__body">
                    <div class="q-form__row q-form__row--2">
                        <div>
                            <label for="start">Start</label>
                            <input type="date" class="form-control @error('start') is-invalid @enderror" id="start" name="start" placeholder="" value="{{ old('start', $project->starts_on?->format('Y-m-d')) ?? '' }}" />
                            <div class="invalid-feedback">
                                @error('start')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div>
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
            </div>

            <div class="q-form-section">
                <div class="q-form-section__head">
                    Mitarbeiter
                    <div class="q-form-section__desc">Die Mitarbeiter, welche in der Auswertung vorhanden sein sollen.</div>
                </div>
                <div class="q-form-section__body">
                    <label for="employee_ids">Mitarbeiter</label>
                    <people-selector inputname="employee_ids[]" :people="{{ $employees }}" :current_people="{{ $currentEmployees ?? 'null' }}" v-cloak></people-selector>
                    <div class="invalid-feedback @error('employee_ids') d-block @enderror">
                        @error('employee_ids')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="q-form-section">
                <div class="q-form-section__head">
                    Leistungen
                    <div class="q-form-section__desc">Die Leistungen, welche in der Auswertung vorhanden sein sollen.</div>
                </div>
                <div class="q-form-section__body">
                    <label for="service_ids">Leistungen</label>
                    <accounting-services-selector inputname="service_ids[]" :services="{{ $services }}" :current_services="{{ $currentServices ?? 'null' }}" v-cloak></accounting-services-selector>
                    <div class="invalid-feedback @error('service_ids') d-block @enderror">
                        @error('service_ids')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="q-form-actions q-form-actions--solo-mobile">
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                    <span class="d-none d-md-inline">PDF erstellen</span>
                    <span class="d-inline d-md-none">Erstellen</span>
                </button>
            </div>
        </form>
    </div>
@endsection
