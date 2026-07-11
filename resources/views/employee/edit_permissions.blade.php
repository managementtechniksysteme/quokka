@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('employee.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#key"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Berechtigungen bearbeiten</div>
                    <h1 class="q-title">{{ $employee->person->name }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('employees.update-permissions', $employee) }}" method="post" novalidate>
            @method('PATCH')
            @include('employee.fields_role', ['employee' => $employee, 'roles' => $roles])

            <div class="q-form-actions">
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#save"></use></svg>
                    Berechtigungen zuweisen
                </button>
            </div>
        </form>

        <form class="q-form needs-validation mt-4" action="{{ route('employees.update-permissions', $employee) }}" method="post" novalidate>
            @method('PATCH')
            @include('employee.fields_permissions', ['employee' => $employee])

            <div class="q-form-actions">
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#save"></use></svg>
                    Berechtigungen speichern
                </button>
            </div>
        </form>
    </div>
@endsection
