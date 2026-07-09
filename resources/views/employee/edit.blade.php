@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('employee.breadcrumb')

        <div class="q-page-head">
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
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('employees.show', $employee) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('employees.edit-permissions', $employee) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#key"></use></svg>Berechtigungen bearbeiten</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#save"></use></svg>
                    Mitarbeiter speichern
                </button>
            </div>
        </form>
    </div>
@endsection
