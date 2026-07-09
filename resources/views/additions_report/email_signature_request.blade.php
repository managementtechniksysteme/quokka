@extends('layouts.app')

@section('content')
    <div class="q-container">

        @include('additions_report.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">{{ $additionsReport->project->name }} #{{ $additionsReport->number }}</div>
                    <h1 class="q-title">Anfrage zur Unterschrift senden</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation mt-4" action="{{ route('additions-reports.email-signature-request', ['additions_report' => $additionsReport, 'redirect' => request()->redirect]) }}" method="post" novalidate>
            @csrf

            <div class="q-form-section">
                <div class="q-form-section__head">
                    Empfänger
                    <div class="q-form-section__desc">Hier kann die gewünschte Email Adresse angegeben werden, an welche eine Anfrage zur Unterschrift gesendet werden soll. Die Email Adresse der Firma wird automatisch eingetragen.</div>
                </div>
                <div class="q-form-section__body">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="email@example.com" value="{{ old('email', optional($additionsReport->project->company->contactPerson)->email ?? $additionsReport->project->company->email) }}" />
                    <div class="invalid-feedback">
                        @error('email'){{ $message }}@else Gib bitte eine gültige E-Mail Adresse ein.@enderror
                    </div>
                </div>
            </div>

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ url()->previous() }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
                    Abbrechen
                </a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#send"></use></svg>
                    Anfrage senden
                </button>
            </div>

        </form>

    </div>
@endsection
