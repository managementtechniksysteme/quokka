@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('finance-groups.index') }}" class="q-appbar__btn" aria-label="Zurück zu Finanzgruppen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $financeGroup->title }}</span>
    @canany(['update', 'delete'], $financeGroup)
        <button class="q-appbar__btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#financeGroupShowActionsSheet" aria-controls="financeGroupShowActionsSheet" aria-label="Aktionen">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>
    @endcanany
@endsection

@section('mobile-detail-sheets')
    <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="financeGroupShowActionsSheet" aria-label="Aktionen">
        <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
        <div class="offcanvas-body">
            <div class="q-sheet__label">Aktionen</div>
            @can('update', $financeGroup)
                <a class="q-row" href="{{ route('finance-groups.edit', $financeGroup) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg></span>
                    <span class="q-row__title">Bearbeiten</span>
                </a>
            @endcan
            @can('delete', $financeGroup)
                <form action="{{ route('finance-groups.destroy', $financeGroup) }}" method="post">
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
@endsection

@section('content')
    <div class="q-container">

        <div class="d-none d-md-block">
            @include('finance_group.breadcrumb')
        </div>

        @unless($financeRecords->isEmpty())
            <div class="q-meta d-flex d-md-none mt-2 pt-1 mb-3">
                <span class="q-chip @if($financeGroup->finance_records_sum_amount < 0) q-chip--danger @endif">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
                    {{ Number::toLocal($financeGroup->finance_records_sum_amount, 2) }}
                </span>
            </div>
        @endunless

        <div class="q-page-head d-none d-md-flex">
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
            <div class="q-card mt-2 mt-md-4">
                <div class="q-card__head">Bemerkungen</div>
                <div class="q-card__body">
                    <div class="markdown">
                        {!! Html::fromMarkdown($financeGroup->comment) !!}
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-4">
            {{-- Same shape as every other tab-embedded list (company's
                 Personen/Projekte tabs, project's Aufgaben/Teilrechnungen/
                 Aktenvermerke tabs): a single @if/@else so exactly one CTA
                 renders at a time — never a header button stacked on top of
                 the empty-state's own button — and both use the same
                 secondary .q-btn style those siblings use throughout
                 (2026-07-22, user report: two CTAs on the empty state read
                 as redundant, and asked why this button was secondary
                 elsewhere — it already was, everywhere but here). --}}
            @if($financeRecords->isEmpty())
                <div class="q-empty-state">
                    <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#layers"></use></svg>
                    <p>Noch keine Finanzeinträge in dieser Gruppe.</p>
                    @can('create', \App\Models\FinanceRecord::class)
                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('finance-records.create', ['finance_group' => $financeGroup]) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Finanzeintrag anlegen
                        </a>
                    @endcan
                </div>
            @else
                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                    <div class="d-none d-md-flex align-items-baseline gap-2">
                        <h2 class="q-subhead">Finanzeinträge</h2>
                        <span class="q-subtitle mt-0">{{ trans_choice('messages.entries', $financeRecords->total()) }}</span>
                    </div>
                    {{-- Mobile: same weight as task/index's own compact-row
                         count (.q-subtitle, small + muted) — this page has
                         no left sub-nav pill to carry the count elsewhere
                         like company/project's tabs do, so it's worth
                         keeping here, but as a big bold heading it read
                         heavier than every other count-in-a-row in the app
                         (2026-07-22, user report). --}}
                    <div class="q-subtitle mb-0 d-md-none">{{ trans_choice('messages.entries', $financeRecords->total()) }}</div>
                    @can('create', \App\Models\FinanceRecord::class)
                        <a class="btn q-btn ms-auto d-inline-flex align-items-center gap-2" href="{{ route('finance-records.create', ['finance_group' => $financeGroup]) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            <span class="d-none d-md-inline">Finanzeintrag anlegen</span>
                            <span class="d-inline d-md-none">Finanzeintrag</span>
                        </a>
                    @endcan
                </div>

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
