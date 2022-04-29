<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col test=muted flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('employees.show', $employee) }}"></a>
            <div class="mw-100 text-truncate">
                {{ $employee->person->name }}
            </div>
            <div class="text-muted d-inline-flex align-items-center">
                <svg class="feather feather-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>
                </svg>
                {{ $employee->holidays }}
                @if($employee->user)
                    <svg class="feather feather-16 ml-2 mr-1 @if($employee->user->trashed()) text-warning @endif">
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
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.edit', $employee) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email senden
                    </a>
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                        Favorisieren
                    </a>
                    @if($employee->user && $employee->user->trashed())
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.access-grant', $employee) }}">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#unlock"></use>
                            </svg>
                            Quokka Zugang entsperren
                        </a>
                    @elseif($employee->user)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.access-deny', $employee) }}">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#lock"></use>
                            </svg>
                            Quokka Zugang sperren
                        </a>
                    @endif
                    <form action="{{ route('employees.destroy', $employee) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
