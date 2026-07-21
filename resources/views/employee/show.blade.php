@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('employees.index') }}" class="q-appbar__btn" aria-label="Zurück zu Mitarbeitern">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $employee->person->name }}</span>
    @canany(['update', 'delete'], $employee)
        <button class="q-appbar__btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#employeeShowActionsSheet" aria-controls="employeeShowActionsSheet" aria-label="Aktionen">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>
    @endcanany
@endsection

@section('mobile-detail-sheets')
    <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="employeeShowActionsSheet" aria-label="Aktionen">
        <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
        <div class="offcanvas-body">
            <div class="q-sheet__label">Aktionen</div>
            @can('update', $employee)
                <a class="q-row" href="{{ route('employees.edit', $employee) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg></span>
                    <span class="q-row__title">Bearbeiten</span>
                </a>
            @endcan
            @can('email', $employee)
                <a class="q-row" href="#">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg></span>
                    <span class="q-row__title">Email versenden</span>
                </a>
            @endcan
            @can('createPdf', $employee)
                <a class="q-row" href="#">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg></span>
                    <span class="q-row__title">PDF erstellen</span>
                </a>
            @endcan
            <a class="q-row" href="#">
                <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg></span>
                <span class="q-row__title">Favorisieren</span>
            </a>
            @if($employee->user && $employee->user->trashed())
                @can('update', $employee)
                    <a class="q-row" href="{{ route('employees.access-grant', $employee) }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#unlock"></use></svg></span>
                        <span class="q-row__title">Quokka Zugang entsperren</span>
                    </a>
                @endcan
            @elseif($employee->user)
                @can('update', $employee)
                    <a class="q-row" href="{{ route('employees.access-deny', $employee) }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#lock"></use></svg></span>
                        <span class="q-row__title">Quokka Zugang sperren</span>
                    </a>
                @endcan
                @can('impersonate', $employee)
                    @if(Session::has('impersonatorId') && Auth::id() === $employee->person_id)
                        <a class="q-row" href="{{ route('employees.impersonate', $employee) }}">
                            <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#person-dash"></use></svg></span>
                            <span class="q-row__title">Zurück zum eigenen Benutzer</span>
                        </a>
                    @elseif(Auth::id() !== $employee->person_id)
                        <a class="q-row" href="{{ route('employees.impersonate', $employee) }}">
                            <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#person-plus"></use></svg></span>
                            <span class="q-row__title">Als Quokka Benutzer anmelden</span>
                        </a>
                    @endif
                @endcan
            @endif
            @can('delete', $employee)
                <form action="{{ route('employees.destroy', $employee) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="q-row q-row--danger">
                        <span class="q-avatar q-avatar--danger"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg></span>
                        <span class="q-row__title">Entfernen</span>
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="q-container">

        <div class="d-none d-md-block">
            @include('employee.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                @include('partials.employee_avatar', ['employee' => $employee])
                <div>
                    <div class="q-eyebrow">Mitarbeiter</div>
                    <h1 class="q-title">{{ $employee->person->name }}</h1>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @can('update', $employee)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('employees.edit', $employee) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="employeeShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="employeeShowDropdown">
                        @can('email', $employee)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $employee)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @if($employee->user && $employee->user->trashed())
                            @can('update', $employee)
                                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.access-grant', $employee) }}">
                                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#unlock"></use></svg>
                                    Quokka Zugang entsperren
                                </a>
                            @endcan
                        @elseif($employee->user)
                            @can('update', $employee)
                                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.access-deny', $employee) }}">
                                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#lock"></use></svg>
                                    Quokka Zugang sperren
                                </a>
                            @endcan
                            @can('impersonate', $employee)
                                @if(Session::has('impersonatorId') && Auth::id() === $employee->person_id)
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.impersonate', $employee) }}">
                                        <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#person-dash"></use></svg>
                                        Zurück zum eigenen Benutzer
                                    </a>
                                @elseif(Auth::id() !== $employee->person_id)
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.impersonate', $employee) }}">
                                        <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#person-plus"></use></svg>
                                        Als Quokka Benutzer anmelden
                                    </a>
                                @endif
                            @endcan
                        @endif
                        @can('delete', $employee)
                            <form action="{{ route('employees.destroy', $employee) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                                    Entfernen
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="q-card mt-2 mt-md-4">
            <div class="q-card__head">Stammdaten</div>
            <div class="q-card__body">

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#person"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Person</div>
                        <div class="q-inforow__value">
                            <a href="{{ route('people.show', $employee->person) }}">{{ $employee->person->name }}</a>
                        </div>
                    </div>
                </div>

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#at"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Quokka Benutzername</div>
                        <div class="q-inforow__value">
                            @if($employee->user && $employee->user->trashed())
                                {{ $employee->user->username }}
                                <span class="q-chip ms-1">
                                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#lock"></use></svg>
                                    gesperrt
                                </span>
                            @elseif($employee->user)
                                {{ $employee->user->username }}
                            @else
                                <span class="q-inforow__value--empty">kein Benutzer angelegt</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#box-arrow-in-right"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Eintrittsdatum</div>
                        <div class="q-inforow__value">
                            @if($employee->entered_on){{ $employee->entered_on }}@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                        </div>
                    </div>
                </div>

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#box-arrow-right"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Austrittsdatum</div>
                        <div class="q-inforow__value">
                            @if($employee->left_on){{ $employee->left_on }}@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                        </div>
                    </div>
                </div>

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sun"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Urlaubstage</div>
                        <div class="q-inforow__value">{{ $employee->holidays }}</div>
                    </div>
                </div>

            </div>
        </div>

        @if($employee->user && count($employee->user->permissions))
            <div class="mt-4">
                <div class="d-flex align-items-start align-items-md-center flex-nowrap gap-2 mb-3">
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-baseline gap-md-2" style="row-gap:.1rem">
                        <h2 class="q-subhead">Berechtigungen</h2>
                        <span class="q-subtitle mt-0">{{ trans_choice('messages.permissions', $employee->user->permissions) }}</span>
                    </div>
                    <button class="btn q-btn d-inline-flex align-items-center gap-2 ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#employeePermissions">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-down"></use></svg>
                        Anzeigen
                    </button>
                </div>
                <div class="collapse" id="employeePermissions">
                    <fieldset disabled>
                        @include('permission.fields', ['permissions' => $employee->user])
                    </fieldset>
                </div>
            </div>
        @endif

    </div>
@endsection
