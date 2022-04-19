@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>Leistungsabrechnung</h3>
        </div>
    </div>
    <div class="container my-4">
        <accounting-selector :current_accounting="{{ $currentAccounting ?? 'null' }}" :projects="{{ $projects }}" :services="{{ $services }}" :employees="{{ $employees }}" :current_employee="{{ $currentEmployee }}" :expand_errors="{{ $expandErrors }}"></accounting-selector>
    </div>
@endsection
