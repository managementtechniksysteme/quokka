@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Übersicht</div>
                    <h1 class="q-title">Finanzübersicht</h1>
                </div>
            </div>
            @can('finances-createpdf')
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('finances.download') }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
        </div>

        {{-- Mobile: the app bar already carries "Finanzübersicht" — the
             desktop header above is redundant here and hidden. The PDF
             action still needs a home though, collapsed to an icon-only
             button (same treatment as e.g. project's task-tab "PDF Liste"
             action) rather than the full-width label button the shared
             .q-page-head mobile rule would otherwise stretch it into
             (2026-07-22, user report). --}}
        @can('finances-createpdf')
            <div class="d-flex d-md-none justify-content-end mb-3">
                <a class="btn q-btn q-btn-icon" href="{{ route('finances.download') }}" aria-label="PDF erstellen">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                </a>
            </div>
        @endcan

        {{-- Overall summary --}}
        <div class="d-flex align-items-center gap-2 mt-2 mt-md-4 mb-3">
            <h2 class="q-subhead">Aktuelle Finanzübersicht</h2>
        </div>

        <div class="q-card">
            @include('partials.finance_stat_row', ['currencyUnit' => $currencyUnit, 'stats' => [
                ['label' => 'Einnahmen', 'value' => $groupTotals['revenue'], 'variant' => 'success'],
                ['label' => 'Ausgaben', 'value' => $groupTotals['expense'], 'variant' => 'danger'],
                ['label' => 'Differenz', 'value' => $groupTotals['revenue'] + $groupTotals['expense'], 'variant' => $groupTotals['revenue'] + $groupTotals['expense'] >= 0 ? 'success' : 'danger'],
            ]])
        </div>

        <div class="q-card mt-3">
            <finance-revenue-expense-chart :revenue="{{ $groupTotals['revenue'] }}" :expense="{{ $groupTotals['expense'] }}" v-cloak></finance-revenue-expense-chart>
        </div>

        {{-- Per-group filter --}}
        <div class="d-flex align-items-center gap-2 mt-4 mb-3">
            <h2 class="q-subhead">Gruppenübersicht</h2>
        </div>

        <div class="q-form-section mb-0">
            <div class="q-form-section__body">
                {{-- Mobile: stack the dropdown above the button instead of
                     side-by-side — a long selected group name has no room
                     to shrink next to a fixed-width button, and without
                     wrapping was pushing the button past the card's edge
                     (2026-07-22, user report). --}}
                <form class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3" action="{{ route('finances.index') }}" method="get">
                    <div class="flex-grow-1">
                        <finance-group-dropdown :finance_groups="{{ $financeGroups }}" :current_finance_group="{{ $currentFinanceGroup ?? 'null' }}" inputname="group" v-cloak></finance-group-dropdown>
                    </div>
                    <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center justify-content-center gap-2 flex-shrink-0">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#eye"></use></svg>
                        Anzeigen
                    </button>
                </form>
            </div>
        </div>

        @if($groupData)
            <div class="q-card mt-3">
                @include('partials.finance_stat_row', ['currencyUnit' => $currencyUnit, 'stats' => [
                    ['label' => 'Einnahmen', 'value' => $groupData['revenue'], 'variant' => 'success'],
                    ['label' => 'Ausgaben', 'value' => $groupData['expense'], 'variant' => 'danger'],
                    ['label' => 'Differenz', 'value' => $groupData['revenue'] + $groupData['expense'], 'variant' => $groupData['revenue'] + $groupData['expense'] >= 0 ? 'success' : 'danger'],
                ]])
            </div>

            <div class="q-card mt-3">
                <finance-revenue-expense-chart :revenue="{{ $groupData['revenue'] }}" :expense="{{ $groupData['expense'] }}" v-cloak></finance-revenue-expense-chart>
            </div>
        @endif

    </div>
@endsection
