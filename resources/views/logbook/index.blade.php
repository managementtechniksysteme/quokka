@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0 d-xl-none">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#book"></use>
                </svg>
                Fahrtenbuch
            </h3>
        </div>
    </div>
    <div class="my-4 my-xl-0 h-100">
        <logbook-selector :current_logbook="{{ $currentLogbook ?? 'null' }}" :places="{{ $places }}" :vehicles="{{ $vehicles }}" :projects="{{ $projects }}" :employees="{{ $employees }}" :current_employee="{{ $currentEmployee }}" :permissions="{{ $permissions }}" :expand_errors="{{ $expandErrors }}" :show_days="{{ $filterDefaultDays ?? 0 }}" :page_size="{{ $pageSize }}"></logbook-selector>
    </div>
@endsection
