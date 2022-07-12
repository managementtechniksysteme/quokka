@extends('project.show')

@section('tab')
    @unless ($project->interimInvoices->isEmpty())
        @can('create', \App\Models\InterimResult::class)
            <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('interim-results.create', ['project' => $project]) }}">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                </svg>
                Teilrechnung anlegen
            </a>
        @endcan
    @endunless

    <div class="mt-3">
        @forelse ($interimInvoices as $interimInvoice)
            @component('interim_invoice.overview_card', [ 'interimInvoice'=> $interimInvoice ])
            @endcomponent

            @if(!$loop->last)
                <hr class="m-0 mx-1" />
            @endif
        @empty
            <div class="text-center">
                <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                <p class="lead text-muted">Dem Projekt {{ $project->name }} sind keine Teilrechnungen zugeordnet.</p>
                @can('create', \App\Models\InterimInvoice::class)
                    <p class="lead">Lege eine neue Teilrechnung an.</p>
                    <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('interim-invoices.create', ['project' => $project]) }}">
                        <svg class="icon icon-20 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Teilrechnung anlegen
                    </a>
                @endcan
            </div>
        @endforelse
    </div>

    <div class="mt-2">
        {{ $interimInvoices->links() }}
    </div>
@endsection
