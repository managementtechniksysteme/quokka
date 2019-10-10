@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>
            Firma bearbeiten
            <small class="text-muted">{{ $company->full_name }}</small>
        </h3>

        <form class="needs-validation mt-4" action="{{ route('companies.update', $company) }}" method="post" novalidate>
            @method('PATCH')
            @component('company.fields', [ 'company' => $company, 'currentAddress' => $currentAddress, 'addresses' => $addresses ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                </svg>
                Firma bearbeiten
            </button>
        </form>
    </div>
@endsection