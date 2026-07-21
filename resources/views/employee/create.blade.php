@extends('layouts.app')

@section('content')
    <div class="q-container">
        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#person"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Mitarbeiter anlegen</div>
                    <h1 class="q-title">Neuer Mitarbeiter</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('employees.store') }}" method="post" novalidate>
            @include('employee.fields', ['employee' => $employee, 'currentPerson' => $currentPerson, 'people' => $people, 'holidaysSteps' => $holidaysSteps, 'currentAvatarColour' => $currentAvatarColour, 'avatarColours' => $avatarColours])

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('employees.index') }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Mitarbeiter speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
