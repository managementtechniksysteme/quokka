<div class="overview-card rounded">
    <div class="mw-100 d-flex flex-grow-1 p-3 align-items-center">

        <div class="mw-100 flex-grow-1 h-100 position-relative">
            <a class="stretched-link outline-none" href="{{ route('vehicles.show', $vehicle) }}"></a>
            <div class="mw-100 text-truncate">
                {{ $vehicle->registration_identifier }}
            </div>
            <div class="text-muted">
                <div class="d-inline-flex align-items-center">
                    <svg class="icon icon-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#truck"></use>
                    </svg>
                    {{ $vehicle->make_model }}

                    @if($vehicle->current_kilometres)
                        <svg class="icon icon-16 ml-2 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#database"></use>
                        </svg>
                        {{ $vehicle->current_kilometres_string }}
                    @endif

                </div>
            </div>
        </div>

        <div class="d-none d-md-block ml-2">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="vehicleOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="vehicleOverviewDropdown">
                    @can('update', $vehicle)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('vehicles.edit', $vehicle) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('email', $vehicle)
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $vehicle)
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
                    @can('delete', $vehicle)
                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="post">
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
