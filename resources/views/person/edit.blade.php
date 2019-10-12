@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>
            Person bearbeiten
            <small class="text-muted">{{ $person->title_prefix }} {{ $person->name }} {{ $person->title_suffix }}</small>
        </h3>

        <form class="needs-validation mt-4" action="{{ route('people.update', $person) }}" method="post" novalidate>
            @method('PATCH')
            @component('person.fields', [ 'person' => $person, 'currentAddress' => $currentAddress,
                'addresses' => $addresses, 'currentCompany' => $currentCompany, 'companies' => $companies ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                </svg>
                Person bearbeiten
            </button>
        </form>
    </div>
@endsection
