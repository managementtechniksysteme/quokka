@extends('layouts.app')

@section('content')
    <div class="q-container">

        @include('finance_group.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <div class="q-avatar q-avatar--icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#layers"></use></svg>
                </div>
                <div>
                    <div class="q-eyebrow">Finanzgruppe</div>
                    <h1 class="q-title">{{ $financeGroup->title }}</h1>
                    @unless($financeRecords->isEmpty())
                        <div class="q-meta">
                            <span class="q-chip @if($financeGroup->finance_records_sum_amount < 0) q-chip--danger @endif">
                                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
                                {{ Number::toLocal($financeGroup->finance_records_sum_amount, 2) }}
                            </span>
                        </div>
                    @endunless
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @can('update', $financeGroup)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('finance-groups.edit', $financeGroup) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="financeGroupShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="financeGroupShowDropdown">
                        @can('delete', $financeGroup)
                            <form action="{{ route('finance-groups.destroy', $financeGroup) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                                    Entfernen
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        @if ($financeGroup->comment)
            <div class="q-card mt-4">
                <div class="q-card__head">Bemerkungen</div>
                <div class="q-card__body">
                    <div class="markdown">
                        {!! Html::fromMarkdown($financeGroup->comment) !!}
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <h2 class="q-subhead">Finanzeinträge</h2>
                @unless($financeRecords->isEmpty())
                    <span class="q-subtitle">{{ trans_choice('messages.entries', $financeRecords->total()) }}</span>
                @endunless
                @can('create', \App\Models\FinanceRecord::class)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2 ms-auto" href="{{ route('finance-records.create', ['finance_group' => $financeGroup]) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Finanzeintrag anlegen
                    </a>
                @endcan
            </div>

            @if($financeRecords->isEmpty())
                <div class="text-center py-5">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    <p class="lead text-muted">Zu der Finanzgruppe {{ $financeGroup->title }} gibt es noch keine Finanzeinträge.</p>
                    @can('create', \App\Models\FinanceRecord::class)
                        <a class="btn btn-primary d-inline-flex align-items-center gap-2" href="{{ route('finance-records.create', ['finance_group' => $financeGroup]) }}">
                            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Finanzeintrag anlegen
                        </a>
                    @endcan
                </div>
            @else
                <div class="q-card q-list">
                    @foreach ($financeRecords as $financeRecord)
                        @include('finance_record.overview_card_content', ['financeRecord' => $financeRecord])
                    @endforeach
                </div>

                <div class="q-card mt-2 px-3 py-2 d-flex align-items-center justify-content-between">
                    <span class="text-muted small text-uppercase fw-semibold" style="letter-spacing:.05em; font-size:.72rem">Summe</span>
                    <div class="q-metric @if($financeGroup->finance_records_sum_amount < 0) q-metric--danger @endif">
                        <div class="q-metric__value q-mono">{{ Number::toLocal($financeGroup->finance_records_sum_amount, 2) }}</div>
                    </div>
                </div>

                <div class="mt-2">
                    {{ $financeRecords->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
