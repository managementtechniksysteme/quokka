@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('finance_group.breadcrumb')

            <h3>
                Finanzgruppe
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $financeGroup->title_string }}
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @can('update', $financeGroup)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('finance-groups.edit', $financeGroup) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('delete', $financeGroup)
                    <form action="{{ route('finance-groups.destroy', $financeGroup) }}" method="post" >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    <div class="container my-4">
        @if ($financeGroup->comment)
            <div class="text-muted d-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                </svg>
                Bemerkungen
            </div>
            <div class="markdown">
                {!! Html::fromMarkdown($financeGroup->comment) !!}
            </div>
        @endif

        <h4>
            Finanzeinträge
            @unless($financeRecords->isEmpty())
                <small class="text-muted">{{ trans_choice('messages.entries', $financeRecords->total()) }}</small>
            @endunless
        </h4>

        @unless($financeRecords->isEmpty())
            <div class="mb-2">
                @can('create', \App\Models\FinanceRecord::class)
                    <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('finance-records.create', ['finance_group' => $financeGroup]) }}">
                        <svg class="icon icon-20 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Finanzeintrag anlegen
                    </a>
                @endcan
            </div>
        @endunless

        <div class="mt-3">
            <div class="mb-4">
                @unless($financeRecords->isEmpty())
                    @component('finance_group.sum_card', [ 'sum' => $financeGroup->finance_records_sum_amount ])
                    @endcomponent
                @endunless
            </div>

            @forelse ($financeRecords as $financeRecord)
                @component('finance_record.overview_card', [ 'financeRecord' => $financeRecord ])
                @endcomponent
            @empty
                <div class="text-center">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
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
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $financeRecords->links() }}
        </div>

    </div>
@endsection
