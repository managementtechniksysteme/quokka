@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0 d-xl-none">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                </svg>
                Leistungsabrechnung
            </h3>
        </div>
    </div>
    <div class="my-4 my-xl-0 h-100">
        <accounting-selector :current_accounting="{{ $currentAccounting ?? 'null' }}" :projects="{{ $projects }}" :services="{{ $services }}" :employees="{{ $employees }}" :current_employee="{{ $currentEmployee }}" :permissions="{{ $permissions }}" services_hour_unit="{{ $servicesHourUnit }}" :min_amount="{{ $minAccountingAmount }}" :expand_errors="{{ $expandErrors }}" :show_days="{{ $filterDefaultDays ?? 0 }}" :page_size="{{ $pageSize }}"></accounting-selector>
    </div>
@endsection
