@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                </svg>
                Finanzeintrag anlegen
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('finance-records.store', ['finance_group' => $financeGroup]) }}" method="post" novalidate>
            @component('finance_record.fields', [ 'financeRecord' => $financeRecord, 'currencyUnit' => $currencyUnit ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Finanzeintrag speichern
            </button>
        </form>
    </div>
@endsection
