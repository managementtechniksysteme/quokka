@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
	  @include('finance_record/breadcrumb')

            <h3>
                <svg class="icon icon-baseline mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                </svg>
                Finanzeintrag bearbeiten
                <small class="text-muted">{{ $financeRecord->title }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('finance-records.update', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}" method="post" novalidate>
            @method('PATCH')
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
