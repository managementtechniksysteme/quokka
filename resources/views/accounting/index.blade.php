@extends('layouts.app')

@section('content')
    <div class="q-container">
        <accounting-selector :current_accounting="{{ $currentAccounting ?? 'null' }}" :projects="{{ $projects }}" :services="{{ $services }}" :employees="{{ $employees }}" :current_employee="{{ $currentEmployee }}" :permissions="{{ $permissions }}" services_hour_unit="{{ $servicesHourUnit }}" :min_amount="{{ $minAccountingAmount }}" :expand_errors="{{ $expandErrors }}" :show_days="{{ $filterDefaultDays ?? 0 }}" :page_size="{{ $pageSize }}"></accounting-selector>
    </div>
@endsection
