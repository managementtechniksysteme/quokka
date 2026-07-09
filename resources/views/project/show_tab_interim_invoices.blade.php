@extends('project.show')

@section('tab')
    @unless ($project->interimInvoices->isEmpty())
        <div class="d-flex align-items-center gap-2 mb-3">
            <h2 class="q-subhead">Teilrechnungen</h2>
            @can('create', \App\Models\InterimInvoice::class)
                <a class="btn q-btn ms-auto d-inline-flex align-items-center gap-2" href="{{ route('interim-invoices.create', ['project' => $project]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Teilrechnung anlegen
                </a>
            @endcan
        </div>

        <div class="q-card q-list">
            @foreach ($interimInvoices as $interimInvoice)
                @include('interim_invoice.overview_card_content', ['interimInvoice' => $interimInvoice])
            @endforeach
        </div>

        <div class="mt-3">
            {{ $interimInvoices->links() }}
        </div>
    @else
        <div class="q-empty-state">
            <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
            <p>Diesem Projekt sind noch keine Teilrechnungen zugeordnet.</p>
            @can('create', \App\Models\InterimInvoice::class)
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('interim-invoices.create', ['project' => $project]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Teilrechnung anlegen
                </a>
            @endcan
        </div>
    @endunless
@endsection
