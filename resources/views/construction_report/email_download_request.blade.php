@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ url()->previous() }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $constructionReport->project->name }} #{{ $constructionReport->number }}</span>
@endsection

@section('content')
    <div class="q-container">

        <div class="d-none d-md-block">
            @include('construction_report.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#link"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">{{ $constructionReport->project->name }} #{{ $constructionReport->number }}</div>
                    <h1 class="q-title">Link zum Herunterladen senden</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation mt-2 mt-md-4" action="{{ route('construction-reports.email-download-request', ['construction_report' => $constructionReport, 'redirect' => request()->redirect]) }}" method="post" novalidate>
            @csrf

            <div class="q-form-section">
                <div class="q-form-section__head">
                    Empfänger
                    <div class="q-form-section__desc">Hier kann die gewünschte Email Adresse angegeben werden, an welche der Download-Link gesendet werden soll. Die Email Adresse der Firma wird automatisch eingetragen.</div>
                </div>
                <div class="q-form-section__body">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="email@example.com" value="{{ old('email', optional($constructionReport->project->company->contactPerson)->email ?? $constructionReport->project->company->email) }}" />
                    <div class="invalid-feedback">
                        @error('email'){{ $message }}@else Gib bitte eine gültige E-Mail Adresse ein.@enderror
                    </div>
                </div>
            </div>

            <div class="q-form-actions q-form-actions--solo-mobile">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ url()->previous() }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
                    Abbrechen
                </a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#send"></use></svg>
                    <span class="d-none d-md-inline">Anfrage senden</span>
                    <span class="d-inline d-md-none">Senden</span>
                </button>
            </div>

        </form>

    </div>
@endsection
