@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('employee.breadcrumb')

            <h3>
                Berechtigungen bearbeiten
                <small class="text-muted">{{ $employee->person->name }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('employees.update-permissions', $employee) }}" method="post" novalidate>
            @method('PATCH')
            @component('employee.fields_role', [ 'employee' => $employee, 'roles' => $roles ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center my-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Berechtigungen zuweisen
            </button>
        </form>

        <form class="needs-validation mt-4" action="{{ route('employees.update-permissions', $employee) }}" method="post" novalidate>
            @method('PATCH')
            @component('employee.fields_permissions', [ 'employee' => $employee ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Berechtigungen speichern
            </button>
        </form>
    </div>
@endsection
