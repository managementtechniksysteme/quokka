@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('interim_invoice.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Teilrechnung bearbeiten</div>
                    <h1 class="q-title">{{ $interimInvoice->title }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('interim-invoices.update', ['project' => $project, 'interim_invoice' => $interimInvoice]) }}" method="post" novalidate>
            @method('PATCH')
            @include('interim_invoice.fields', ['interimInvoice' => $interimInvoice, 'currencyUnit' => $currencyUnit])

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('projects.show', $project) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#save"></use></svg>
                    Teilrechnung speichern
                </button>
            </div>
        </form>
    </div>
@endsection
