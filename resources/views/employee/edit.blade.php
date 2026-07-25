@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('employees.show', $employee) }}" class="q-appbar__btn" aria-label="Abbrechen">
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
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#person"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Mitarbeiter bearbeiten</div>
                    <h1 class="q-title">{{ $employee->person->name }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('employees.update', $employee) }}" method="post" novalidate>
            @method('PATCH')
            @include('employee.fields', ['employee' => $employee, 'currentPerson' => $currentPerson, 'people' => $people, 'holidaysSteps' => $holidaysSteps, 'currentAvatarColour' => $currentAvatarColour, 'avatarColours' => $avatarColours])

            <div class="q-form-actions">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ route('employees.show', $employee) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('employees.edit-permissions', $employee) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#key"></use></svg><span class="d-inline d-md-none">Berechtigungen</span><span class="d-none d-md-inline">Berechtigungen bearbeiten</span></a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Mitarbeiter speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
