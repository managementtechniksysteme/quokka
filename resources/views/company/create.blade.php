@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>Firma anlegen</h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('companies.store') }}" method="post" novalidate>
            @component('company.fields', [ 'company' => $company, 'currentAddress' => $currentAddress, 'currentOperatorAddress' => $currentOperatorAddress, 'addresses' => $addresses ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Firma speichern
            </button>
        </form>
    </div>
@endsection
