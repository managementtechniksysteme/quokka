@extends('layouts.app')

@section('content')
    <div class="q-container">
        <logbook-selector :current_logbook="{{ $currentLogbook ?? 'null' }}" :places="{{ $places }}" :vehicles="{{ $vehicles }}" :projects="{{ $projects }}" :employees="{{ $employees }}" :current_employee="{{ $currentEmployee }}" :permissions="{{ $permissions }}" :expand_errors="{{ $expandErrors }}" :show_days="{{ $filterDefaultDays ?? 0 }}" :page_size="{{ $pageSize }}"></logbook-selector>
    </div>
@endsection
