@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('wage-services.index') }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
    </a>
    <span class="q-appbar__title">Lohndienstleistung anlegen</span>
@endsection

@section('content')
    <div class="q-container">
        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#cpu"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Lohndienstleistung anlegen</div>
                    <h1 class="q-title">Neue Lohndienstleistung</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('wage-services.store') }}" method="post" novalidate>
            @include('wage_service.fields', ['wageService' => $wageService, 'units' => $units, 'currentUnit' => $currentUnit])

            <div class="q-form-actions q-form-actions--solo-mobile">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ route('wage-services.index') }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Lohndienstleistung speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
