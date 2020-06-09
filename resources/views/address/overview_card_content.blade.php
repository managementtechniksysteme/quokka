<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('addresses.show', $address) }}"></a>
            <p class="m-0">
                {{ $address->street_number }}
            </p>
            <p class="text-muted m-0">
                {{ $address->postcode }} {{ $address->city }}
            </p>
        </div>

        <div class="d-none d-sm-block col-sm-auto text-right">
            <span class="text-muted d-inline-flex align-items-center">
                <svg class="feather feather-12 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                </svg>
                {{ $address->companies_count }}
            </span>

            <span class="text-muted d-inline-flex align-items-center ml-2">
                <svg class="feather feather-12 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                </svg>
                {{ $address->people_count }}
            </span>
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="addressOverviewDropdown" data-toggle="dropdown"></button>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('addresses.edit', $address) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Adresse bearbeiten
                    </a>

                    <form action="{{ route('addresses.destroy', $address) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Adresse entfernen
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
