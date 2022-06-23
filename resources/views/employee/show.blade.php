@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('employee.breadcrumb')

            <h3>
                Mitarbeiter
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $employee->person->name }}
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @can('update', $employee)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('employees.edit', $employee) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('email', $employee)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $employee)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                @endcan
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                @if($employee->user && $employee->user->trashed())
                    @can('update', $employee)
                        <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('employees.access-grant', $employee) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#unlock"></use>
                            </svg>
                            Quokka Zugang entsperren
                        </a>
                    @endcan
                @elseif($employee->user)
                    @can('update', $employee)
                        <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('employees.access-deny', $employee) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#lock"></use>
                            </svg>
                            Quokka Zugang sperren
                        </a>
                    @endcan
                    @can('impersonate', $employee)
                        @if(Session::has('impersonatorId') && Auth::id() === $employee->person_id)
                            <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('employees.impersonate', $employee) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user-minus"></use>
                                </svg>
                                Zurück zum eigenen Benutzer
                            </a>
                        @elseif(Auth::id() !== $employee->person_id)
                            <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('employees.impersonate', $employee) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user-plus"></use>
                                </svg>
                                Als Quokka Benutzer anmelden
                            </a>
                        @endif
                    @endcan
                @endif
                @can('delete', $employee)
                    <form action="{{ route('employees.destroy', $employee) }}" method="post" >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                @endcan
            </div>

        </div>
    </div>

    <div class="container my-4">
        <div class="row mt-3 mt-md-4">
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                    </svg>
                    Person
                </div>
            </div>
            <div class="col">
                <a href="{{ route('people.show', $employee->person) }}">{{ $employee->person->name }}</a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                    </svg>
                    Quokka Benutzername
                </div>
            </div>
            <div class="col">
                @if($employee->user && $employee->user->trashed())
                    {{ $employee->user->username }} (Zugang gesperrt)
                @elseif($employee->user)
                    {{ $employee->user->username }}
                @else
                    kein Benutzer angelegt
                @endif

            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                    </svg>
                    Eintrittsdatum
                </div>
            </div>
            <div class="col">
                {{ $employee->entered_on }}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                    </svg>
                    Austrittsdatum
                </div>
            </div>
            <div class="col">
                {{ $employee->left_on ?? 'nicht angegeben' }}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>
                    </svg>
                    Urlaubstage
                </div>
            </div>
            <div class="col">
                {{ $employee->holidays }}
            </div>
        </div>

        @if(count($employee->user->permissions))
            <h4 class="mt-5">
                Berechtigungen
                <small class="text-muted">
                    {{ trans_choice('messages.elements', $employee->user->permissions) }}
                    <a class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center ml-2" type="button"  data-toggle="collapse" data-target="#permissions">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Anzeigen
                    </a>
                </small>
            </h4>

            <div class="collapse" id="permissions">
                <fieldset disabled>
                    @component('permission.fields', [ 'permissions' => $employee->user ])
                    @endcomponent
                </fieldset>
            </div>
        @endif
    </div>
@endsection
