@extends('project.show')

@section('tab')
    @if ($financeGroup && $financeRecords && !$financeRecords->isEmpty())
        @can('create', \App\Models\FinanceRecord::class)
            <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('finance-records.create', ['finance_group' => $financeGroup]) }}">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                </svg>
                Finanzeintrag anlegen
            </a>
        @endcan
    @endif

    <div class="mt-3">
        <div class="mb-4">
            @if($financeRecords && !$financeRecords->isEmpty())
                @component('finance_group.sum_card', [ 'sum' => $financeGroup->finance_records_sum_amount ])
                @endcomponent
            @endif
        </div>

        @if($financeRecords && !$financeRecords->isEmpty())
            @foreach ($financeRecords as $financeRecord)
                @component('finance_record.overview_card', [ 'financeRecord' => $financeRecord ])
                @endcomponent
            @endforeach

            <div class="mt-2">
                {{ $financeRecords->links() }}
            </div>
        @else
            <div class="text-center">
                <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                @unless($financeGroup)
                    <p class="lead text-muted">Zum Projekt {{ $project->name }} gibt es noch keine Finanzgruppe.</p>
                    @can('create', \App\Models\FinanceGroup::class)
                        <p class="lead">Lege einen neue Finanzgruppe an.</p>
                        <a class="btn btn-lg btn-primary d-inline-flex align-items-center" href="{{ route('finance-groups.create', ['project' => $project->id]) }}">
                            <svg class="icon icon-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                            </svg>
                            Finanzgruppe anlegen
                        </a>
                    @endcan
                @endunless
                @if($financeGroup && $financeRecords->isEmpty())
                    <p class="lead text-muted">Zu der Finanzgruppe {{ $financeGroup->title_string }} gibt es noch keine Finanzeinträge.</p>
                    @can('create', \App\Models\FinanceRecord::class)
                        <p class="lead">Lege einen neuen Finanzeintrag an.</p>
                        <a class="btn btn-lg btn-primary d-inline-flex align-items-center" href="{{ route('finance-records.create', ['finance_group' => $financeGroup]) }}">
                            <svg class="icon icon-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                            </svg>
                            Finanzeintrag anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @endif
    </div>
@endsection
