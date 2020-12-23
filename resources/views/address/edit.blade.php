@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('address.breadcrumb')

            <h3>
                Adresse bearbeiten
                <small class="text-muted">{{ $address->address_line }}</small>
            </h3>
        </div>
    </div>

    <div class="container mt-4">
        <form class="needs-validation mt-4" action="{{ route('addresses.update', $address) }}" method="post" novalidate>
            @method('PATCH')
            @component('address.fields', [ 'address' => $address ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Adresse speichern
            </button>
        </form>
    </div>
@endsection
