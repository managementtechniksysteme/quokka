<div class="overview-card rounded">
    <div class="mw-100 d-flex flex-grow-1 p-3 align-items-center">

        <div class="mw-100 d-flex flex-grow-1 h-100 align-items-center">

            <div class="mw-100 flex-grow-1 position-relative">
                <a class="stretched-link outline-none" href="{{ route('companies.show', $company) }}"></a>

                <div class="mw-100 text-truncate">
                    {{ $company->full_name }}
                </div>
                <div class="text-muted d-inline-flex align-items-center">
                    <svg class="icon icon-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                    </svg>
                    <span class="mw-100 text-truncate">
                        {{ optional($company->address->first())->address_line ?? 'keine Adresse' }}
                    </span>
                </div>
            </div>


            <div class="d-none d-sm-block ml-2">
                @can('viewAny', \App\Models\Project::class)
                    <a class="text-muted d-inline-flex align-items-center" href="{{ route('companies.show', [$company, 'tab' => 'projects']) }}">
                        <svg class="icon icon-16 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                        </svg>
                        {{ $company->projects_count }}
                    </a>
                @endcan

                @can('viewAny', \App\Models\People::class)
                    <a class="text-muted d-inline-flex align-items-center ml-2" href="{{ route('companies.show', [$company, 'tab' => 'people']) }}">
                        <svg class="icon icon-16 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                        </svg>
                        {{ $company->people_count }}
                    </a>
                @endcan
            </div>

            <div class="d-none d-md-block ml-2">
                <div class="dropdown d-inline">
                    <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="companyOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="companyOverviewDropdown">
                        @can('update', $company)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('companies.edit', $company) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                                </svg>
                                Bearbeiten
                            </a>
                        @endcan
                        @can('email', $company)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                                </svg>
                                Email senden
                            </a>
                        @endcan
                        @can('createPdf', $company)
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
                        @can('delete', $company)
                            <form action="{{ route('companies.destroy', $company) }}" method="post">
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
</div>
