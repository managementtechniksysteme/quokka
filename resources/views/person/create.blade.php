@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Person anlegen</h3>

        <form class="needs-validation mt-4" action="{{ route('people.store') }}" method="post" novalidate>
            @component('person.fields', [ 'person' => $person, 'currentAddress' => $currentAddress,
                'addresses' => $addresses, 'currentCompany' => $currentCompany, 'companies' => $companies ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Person speichern
            </button>
        </form>
    </div>
@endsection
