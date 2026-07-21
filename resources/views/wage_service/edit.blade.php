@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('wage_service.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#cpu"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Lohndienstleistung bearbeiten</div>
                    <h1 class="q-title">{{ $wageService->name }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('wage-services.update', $wageService) }}" method="post" novalidate>
            @method('PATCH')
            @include('wage_service.fields', ['wageService' => $wageService, 'units' => $units, 'currentUnit' => $currentUnit])

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('wage-services.show', $wageService) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Lohndienstleistung speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
