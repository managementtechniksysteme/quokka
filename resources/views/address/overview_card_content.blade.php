<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('addresses.show', $address) }}"></a>
            <p class="m-0 ms-100 text-truncate">
                {{ $address->name }}
            </p>
            <p class="text-muted m-0 ms-100 text-truncate">
                {{ $address->address_line }}
            </p>
        </div>

        <div class="d-none d-sm-block col-sm-auto text-right">
            <span class="text-muted d-inline-flex align-items-center">
                <svg class="icon icon-12 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                </svg>
                {{ $address->companies_count }}
            </span>

            <span class="text-muted d-inline-flex align-items-center ml-2">
                <svg class="icon icon-12 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                </svg>
                {{ $address->people_count }}
            </span>
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="addressOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="addressOverviewDropdown">
                    @can('update', $address)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('addresses.edit', $address) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('email', $address)
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $address)
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
                    @can('delete', $address)
                        <form action="{{ route('addresses.destroy', $address) }}" method="post">
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
