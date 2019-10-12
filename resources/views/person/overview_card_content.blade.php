<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('people.show', $person) }}"></a>
            <p class="m-0">
                {{ $person->name }}
            </p>
            <p class="text-muted d-inline-flex align-items-center m-0">
                @if(isset($secondaryInformation))
                    @switch($secondaryInformation)
                        @case('address')
                            <svg class="feather feather-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                            </svg>
                            {{ optional($person->address)->first()->address_line ?? 'keine Adresse angegeben' }}
                            @break
                        @default
                            <svg class="feather feather-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                            </svg>
                            {{ optional($person->company)->name ?? 'keine Firma angegeben' }}
                            @break
                    @endswitch()
                @else
                    <svg class="feather feather-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                    </svg>
                    {{ optional($person->company)->name ?? 'keine Firma angegeben' }}
                @endif
            </p>
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="companyOverviewDropdown" data-toggle="dropdown"></button>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('people.edit', $person) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Person bearbeiten
                    </a>

                    <form action="{{ route('people.destroy', $person) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Person entfernen
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
