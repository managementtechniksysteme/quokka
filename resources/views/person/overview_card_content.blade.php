<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('people.show', $person) }}"></a>
            <div class="mw-100 text-truncate">
                {{ $person->name }}
            </div>
            <div class="text-muted d-inline-flex align-items-center">
                @if(isset($secondaryInformation))
                    @switch($secondaryInformation)
                        @case('address')
                            <svg class="feather feather-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                            </svg>
                            <span class="mw-100 text-truncate">
                                {{ optional($person->address)->first()->address_line ?? 'keine Adresse' }}
                            </span>
                            @break
                        @default
                            <svg class="feather feather-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                            </svg>
                            <span class="mw-100 text-truncate">
                                {{ optional($person->company)->name ?? 'keine Firma' }}
                            </span>
                            @break
                    @endswitch()
                @else
                    <svg class="feather feather-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                    </svg>
                    <span class="mw-100 text-truncate">
                        {{ optional($person->company)->name ?? 'keine Firma' }}
                    </span>
                @endif
            </div>
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="personOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="personOverviewDropdown">
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('people.edit', $person) }}">
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
                    <form action="{{ route('people.destroy', ['person' => $person, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
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
