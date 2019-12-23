@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Adresse anlegen</h3>

        <form class="needs-validation mt-4" action="{{ route('addresses.store') }}" method="post" novalidate>
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
