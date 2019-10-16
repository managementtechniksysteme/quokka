@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>
            Adresse bearbeiten
            <small class="text-muted">{{ $address->address_line }}</small>
        </h3>

        <form class="needs-validation mt-4" action="{{ route('addresses.update', $address) }}" method="post" novalidate>
            @method('PATCH')
            @component('address.fields', [ 'address' => $address ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                </svg>
                Adresse bearbeiten
            </button>
        </form>
    </div>
@endsection
