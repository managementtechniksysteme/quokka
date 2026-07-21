@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('employees.edit', $employee) }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $employee->person->name }}</span>
@endsection

@section('content')
    <div class="q-container">
        <div class="d-none d-md-block">
            @include('employee.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
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

        <form class="q-form needs-validation mt-2 mt-md-0" action="{{ route('employees.update-permissions', $employee) }}" method="post" novalidate>
            @method('PATCH')
            @include('employee.fields_role', ['employee' => $employee, 'roles' => $roles])
        </form>

        <form class="q-form needs-validation mt-4" action="{{ route('employees.update-permissions', $employee) }}" method="post" novalidate>
            @method('PATCH')
            @include('employee.fields_permissions', ['employee' => $employee])

            <div class="q-form-actions q-form-actions--solo-mobile">
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Berechtigungen speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
