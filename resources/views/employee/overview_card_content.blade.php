<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col test=muted flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('employees.show', $employee) }}"></a>
            <div class="mw-100 text-truncate">
                {{ $employee->person->name }}
            </div>
            <div class="text-muted d-inline-flex align-items-center">
                <svg class="icon icon-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>
                </svg>
                {{ $employee->holidays }}
                @if($employee->user)
                    <svg class="icon icon-16 ml-2 mr-1 @if($employee->user->trashed()) text-warning @endif">
                        @if($employee->user->trashed())
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user-x"></use>
                        @else
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                        @endif
                    </svg>
                    {{ $employee->user->username }}
                @endif
            </div>
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="employeeOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="employeeOverviewDropdown">
                    @can('update', $employee)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.edit', $employee) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('email', $employee)
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $employee)
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                            </svg>
                            PDF erstellen
                        </a>
                    @endcan
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                        Favorisieren
                    </a>
                    @if($employee->user && $employee->user->trashed())
                        @can('update', $employee)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.access-grant', $employee) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#unlock"></use>
                                </svg>
                                Quokka Zugang entsperren
                            </a>
                        @endcan
                    @elseif($employee->user)
                        @can('update', $employee)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.access-deny', $employee) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#lock"></use>
                                </svg>
                                Quokka Zugang sperren
                            </a>
                        @endcan
                        @if(Session::has('impersonatorId') && Auth::id() === $employee->person_id)
                            @can('impersonate', $employee)
                                <form action="{{ route('employees.stop-impersonation', $employee) }}" method="post" >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="dropdown-item d-inline-flex align-items-center">
                                        <svg class="icon icon-16 mr-2">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user-minus"></use>
                                        </svg>
                                        Zurück zum eigenen Benutzer
                                    </button>
                                </form>
                            @endcan
                        @elseif(Auth::id() !== $employee->person_id)
                            @can('impersonate', $employee)
                                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.start-impersonation', $employee) }}">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user-plus"></use>
                                    </svg>
                                    Als Quokka Benutzer anmelden
                                </a>
                            @endcan
                        @endif
                    @endif
                    @can('delete', $employee)
                        <form action="{{ route('employees.destroy', $employee) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
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

    </div>
</div>
