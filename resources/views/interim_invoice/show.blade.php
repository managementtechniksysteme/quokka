@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('projects.show', $interimInvoice->project) }}" class="q-appbar__btn" aria-label="Zurück zum Projekt">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $interimInvoice->title }}</span>
    @canany(['update', 'delete'], $interimInvoice)
        <button class="q-appbar__btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#interimInvoiceShowActionsSheet" aria-controls="interimInvoiceShowActionsSheet" aria-label="Aktionen">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>
    @endcanany
@endsection

@section('mobile-detail-sheets')
    @canany(['update', 'delete'], $interimInvoice)
        <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="interimInvoiceShowActionsSheet" aria-label="Aktionen">
            <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
            <div class="offcanvas-body">
                <div class="q-sheet__label">Aktionen</div>
                @can('update', $interimInvoice)
                    <a class="q-row" href="{{ route('interim-invoices.edit', ['project' => $interimInvoice->project, 'interim_invoice' => $interimInvoice]) }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg></span>
                        <span class="q-row__title">Bearbeiten</span>
                    </a>
                @endcan
                @can('delete', $interimInvoice)
                    <form action="{{ route('interim-invoices.destroy', ['project' => $interimInvoice->project, 'interim_invoice' => $interimInvoice]) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="q-row q-row--danger">
                            <span class="q-avatar q-avatar--danger"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg></span>
                            <span class="q-row__title">Entfernen</span>
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    @endcanany
@endsection

@section('content')
    <div class="q-container">
        <div class="d-none d-md-block">
            @include('interim_invoice.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#file-text"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Teilrechnung</div>
                    <h1 class="q-title">{{ $interimInvoice->title }}</h1>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @can('update', $interimInvoice)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('interim-invoices.edit', ['project' => $interimInvoice->project, 'interim_invoice' => $interimInvoice]) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('delete', $interimInvoice)
                    <form action="{{ route('interim-invoices.destroy', ['project' => $interimInvoice->project, 'interim_invoice' => $interimInvoice]) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center gap-2">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                            Entfernen
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        <div class="q-card mb-3 mt-2 mt-md-0">
            <div class="q-card__body">
                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Datum</div>
                        <div class="q-inforow__value">{{ $interimInvoice->billed_on }}</div>
                    </div>
                </div>

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Summe</div>
                        <div class="q-inforow__value q-mono">{{ Number::toLocal($interimInvoice->amount, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if ($interimInvoice->comment)
            <div class="q-card">
                <div class="q-card__head">Bemerkungen</div>
                <div class="q-card__body">
                    <div class="markdown">
                        {!! Html::fromMarkdown($interimInvoice->comment) !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
