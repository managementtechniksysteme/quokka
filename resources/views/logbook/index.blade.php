@extends('layouts.app')

{{-- Mobile app bar: same badge+title shape every other index-style page
     uses — see accounting/index.blade.php's identical comment.
     Create/Filter/Auswertung(/Save) icons are teleported in from
     LogbookSelector.vue itself (2026-07-22). --}}
@section('mobile-detail-bar')
    <span class="q-appbar__badge q-appbar__badge--tint">
        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#journal"></use></svg>
    </span>
    <span class="q-appbar__title">Fahrtenbuch</span>
    <div id="logbookMobileActions" class="d-flex align-items-center gap-2"></div>
@endsection

@section('content')
    <div class="q-container">
        <logbook-selector :current_logbook="{{ $currentLogbook ?? 'null' }}" :places="{{ $places }}" :vehicles="{{ $vehicles }}" :projects="{{ $projects }}" :employees="{{ $employees }}" :current_employee="{{ $currentEmployee }}" :permissions="{{ $permissions }}" :expand_errors="{{ $expandErrors }}" :show_days="{{ $filterDefaultDays ?? 0 }}" :page_size="{{ $pageSize }}"></logbook-selector>
    </div>
@endsection
