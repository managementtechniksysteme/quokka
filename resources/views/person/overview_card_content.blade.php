<div class="overview-card rounded">
    <div class="mw-100 d-flex flex-grow-1 p-3 align-items-center">

        <div class="mw-100 flex-grow-1 h-100 position-relative">
            <a class="stretched-link outline-none" href="{{ route('people.show', $person) }}"></a>
            <div class="mw-100 text-truncate">
                {{ $person->name }}
            </div>
            <div class="mw-100 text-muted d-inline-flex align-items-center">
                @if(isset($secondaryInformation))
                    @switch($secondaryInformation)
                        @case('address')
                            <svg class="icon icon-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                            </svg>
                            <span class="mw-100 text-truncate">
                                {{ optional($person->address)->first()->address_line ?? 'keine Adresse' }}
                            </span>
                            @break
                        @default
                            <svg class="icon icon-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                            </svg>
                            <span class="mw-100 text-truncate">
                                {{ optional($person->company)->name ?? 'keine Firma' }}
                            </span>
                            @break
                    @endswitch()
                @else
                    <svg class="icon icon-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                    </svg>
                    <span class="mw-100 text-truncate">
                        {{ optional($person->company)->name ?? 'keine Firma' }}
                    </span>
                @endif
            </div>
        </div>

        <div class="d-none d-md-block ml-2">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="personOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="personOverviewDropdown">
                    @can('update', $person)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('people.edit', $person) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('email', $person)
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $person)
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
                    @can('delete', $person)
                        <form action="{{ route('people.destroy', ['person' => $person, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
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
