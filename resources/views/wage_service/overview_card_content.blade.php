<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('wage-services.show', $wageService) }}"></a>
            <div class="mw-100 text-truncate">
                {{ $wageService->name }}
            </div>
            <div class="text-muted">
                <div class="d-inline-flex align-items-center">
                    <svg class="feather feather-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#circle"></use>
                    </svg>
                    {{ $wageService->unit }}
                    <svg class="feather feather-16 ml-2 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                    </svg>
                    <span class="mw-100 text-truncate">
                        {{ $wageService->description }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="wageServiceOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="wageServiceOverviewDropdown">
                    @can('update', $wageService)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('wage-services.edit', $wageService) }}">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('email', $wageService)
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $wageService)
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                            </svg>
                            PDF erstellen
                        </a>
                    @endcan
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                        Favorisieren
                    </a>
                    @can('delete', $wageService)
                        <form action="{{ route('wage-services.destroy', $wageService) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-2">
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
