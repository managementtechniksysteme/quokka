@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
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

        {{-- Overall summary --}}
        <div class="d-flex align-items-center gap-2 mt-4 mb-3">
            <h2 class="q-subhead">Aktuelle Finanzübersicht</h2>
        </div>

        <div class="q-card">
            <div class="d-flex flex-wrap">
                <div class="flex-fill p-4 text-center">
                    <div class="q-eyebrow">Einnahmen</div>
                    <div class="q-mono fw-bold" style="font-size: 1.5rem; color: var(--q-green)">{{ Number::toLocal($groupTotals['revenue'], 2) }} {{ $currencyUnit }}</div>
                </div>
                <div class="flex-fill p-4 text-center" style="border-left: 1px solid var(--q-border-2)">
                    <div class="q-eyebrow">Ausgaben</div>
                    <div class="q-mono fw-bold" style="font-size: 1.5rem; color: var(--q-red)">{{ Number::toLocal($groupTotals['expense'], 2) }} {{ $currencyUnit }}</div>
                </div>
                <div class="flex-fill p-4 text-center" style="border-left: 1px solid var(--q-border-2)">
                    <div class="q-eyebrow">Differenz</div>
                    <div class="q-mono fw-bold" style="font-size: 1.5rem; color: var(--{{ $groupTotals['revenue'] + $groupTotals['expense'] >= 0 ? 'q-green' : 'q-red' }})">{{ Number::toLocal($groupTotals['revenue'] + $groupTotals['expense'], 2) }} {{ $currencyUnit }}</div>
                </div>
            </div>
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
                <form class="d-flex align-items-center gap-3" action="{{ route('finances.index') }}" method="get">
                    <div class="flex-grow-1">
                        <finance-group-dropdown :finance_groups="{{ $financeGroups }}" :current_finance_group="{{ $currentFinanceGroup ?? 'null' }}" inputname="group" v-cloak></finance-group-dropdown>
                    </div>
                    <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2 flex-shrink-0">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#eye"></use></svg>
                        Anzeigen
                    </button>
                </form>
            </div>
        </div>

        @if($groupData)
            <div class="q-card mt-3">
                <div class="d-flex flex-wrap">
                    <div class="flex-fill p-4 text-center">
                        <div class="q-eyebrow">Einnahmen</div>
                        <div class="q-mono fw-bold" style="font-size: 1.5rem; color: var(--q-green)">{{ Number::toLocal($groupData['revenue'], 2) }} {{ $currencyUnit }}</div>
                    </div>
                    <div class="flex-fill p-4 text-center" style="border-left: 1px solid var(--q-border-2)">
                        <div class="q-eyebrow">Ausgaben</div>
                        <div class="q-mono fw-bold" style="font-size: 1.5rem; color: var(--q-red)">{{ Number::toLocal($groupData['expense'], 2) }} {{ $currencyUnit }}</div>
                    </div>
                    <div class="flex-fill p-4 text-center" style="border-left: 1px solid var(--q-border-2)">
                        <div class="q-eyebrow">Differenz</div>
                        <div class="q-mono fw-bold" style="font-size: 1.5rem; color: var(--{{ $groupData['revenue'] + $groupData['expense'] >= 0 ? 'q-green' : 'q-red' }})">{{ Number::toLocal($groupData['revenue'] + $groupData['expense'], 2) }} {{ $currencyUnit }}</div>
                    </div>
                </div>
            </div>

            <div class="q-card mt-3">
                <finance-revenue-expense-chart :revenue="{{ $groupData['revenue'] }}" :expense="{{ $groupData['expense'] }}" v-cloak></finance-revenue-expense-chart>
            </div>
        @endif

    </div>
@endsection
