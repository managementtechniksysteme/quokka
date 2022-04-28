@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>Mitarbeiter anlegen</h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('employees.store') }}" method="post" novalidate>
            @component('employee.fields', [ 'employee' => $employee, 'currentPerson' => $currentPerson, 'people' => $people, 'holidaysSteps' => $holidaysSteps, 'currentAvatarColour' => $currentAvatarColour, 'avatarColours' => $avatarColours ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Mitarbeiter speichern
            </button>
        </form>
    </div>
@endsection
