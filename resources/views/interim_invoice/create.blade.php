@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('projects.show', $project) }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
    </a>
    <span class="q-appbar__title">Teilrechnung anlegen</span>
@endsection

@section('content')
    <div class="q-container">
        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Teilrechnung anlegen</div>
                    <h1 class="q-title">Neue Teilrechnung</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('interim-invoices.store', ['project' => $project]) }}" method="post" novalidate>
            @include('interim_invoice.fields', ['interimInvoice' => $interimInvoice, 'currencyUnit' => $currencyUnit])

            <div class="q-form-actions q-form-actions--solo-mobile">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ route('projects.show', $project) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Teilrechnung speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
