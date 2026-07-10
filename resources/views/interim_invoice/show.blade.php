@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('interim_invoice.breadcrumb')

        <div class="q-page-head">
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

        <div class="q-card mb-3">
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
