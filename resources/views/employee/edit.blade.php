@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('employee.breadcrumb')

            <h3>
                Mitarbeiter bearbeiten
                <small class="text-muted">{{ $employee->person->full_name }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('employees.update', $employee) }}" method="post" novalidate>
            @method('PATCH')
            @component('employee.fields', [ 'employee' => $employee, 'currentPerson' => $currentPerson, 'people' => $people, 'holidaysSteps' => $holidaysSteps, 'currentAvatarColour' => $currentAvatarColour, 'avatarColours' => $avatarColours ])
            @endcomponent

            <a class="btn btn-outline-secondary d-inline-flex align-items-center mt-4" href="{{ route('employees.edit-permissions', $employee) }}">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#key"></use>
                </svg>
                Berechtigungen bearbeiten
            </a>

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Mitarbeiter speichern
            </button>
        </form>
    </div>
@endsection
