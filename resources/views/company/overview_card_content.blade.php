<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('companies.show', $company) }}"></a>
            <div>
                {{ $company->full_name }}
            </div>
            <div class="text-muted d-inline-flex align-items-center">
                <svg class="feather feather-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                </svg>
                {{ optional($company->address->first())->address_line ?? 'keine Adresse angegeben' }}
            </div>
        </div>

        <div class="d-none d-sm-block col-sm-auto text-right">
            <a class="text-muted d-inline-flex align-items-center" href="{{ route('companies.show', [$company, 'tab' => 'projects']) }}">
                <svg class="feather feather-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                </svg>
                {{ $company->projects_count }}
            </a>

            <a class="text-muted d-inline-flex align-items-center ml-2" href="{{ route('companies.show', [$company, 'tab' => 'people']) }}">
                <svg class="feather feather-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                </svg>
                {{ $company->people_count }}
            </a>
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="companyOverviewDropdown" data-toggle="dropdown"></button>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('companies.edit', $company) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Firma bearbeiten
                    </a>

                    <form action="{{ route('companies.destroy', $company) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Firma entfernen
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
