@extends('layouts.app')

{{-- Mobile app bar: same badge+title shape every other index-style page
     uses (search/notification/settings/...) — this is an index page, not a
     record detail page, so it keeps the badge rather than dropping to the
     detail pages' back+title+kebab shape. Filter/Create/Auswertung(/Save)
     icons are teleported in from AccountingSelector.vue itself so they
     share its reactive state (unsaved count, sheets) instead of a global
     event bus (2026-07-22). --}}
@section('mobile-detail-bar')
    <span class="q-appbar__badge q-appbar__badge--tint">
        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clock"></use></svg>
    </span>
    <span class="q-appbar__title">Abrechnung</span>
    <div id="accountingMobileActions" class="d-flex align-items-center gap-2"></div>
@endsection

@section('content')
    <div class="q-container">
        <accounting-selector :current_accounting="{{ $currentAccounting ?? 'null' }}" :projects="{{ $projects }}" :services="{{ $services }}" :employees="{{ $employees }}" :current_employee="{{ $currentEmployee }}" :permissions="{{ $permissions }}" services_hour_unit="{{ $servicesHourUnit }}" :min_amount="{{ $minAccountingAmount }}" :expand_errors="{{ $expandErrors }}" :show_days="{{ $filterDefaultDays ?? 0 }}" :page_size="{{ $pageSize }}"></accounting-selector>
    </div>
@endsection
