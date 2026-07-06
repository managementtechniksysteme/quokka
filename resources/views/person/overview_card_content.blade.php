<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('people.show', $person) }}"></a>

    <span class="q-avatar q-avatar--round q-avatar--{{ $person->avatar_colour }}">{{ $person->initials }}</span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $person->name }}</div>
        <div class="q-meta">
            @if(($secondaryInformation ?? '') === 'address')
                <span class="q-chip">
                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use></svg>
                    <span class="text-truncate">{{ optional($person->address->first())->address_line ?? 'keine Adresse' }}</span>
                </span>
            @else
                <span class="q-chip">
                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use></svg>
                    <span class="text-truncate">{{ optional($person->company)->name ?? 'keine Firma' }}</span>
                </span>
            @endif

            @if($person->role)
                <span class="q-chip">
                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#award"></use></svg>
                    <span class="text-truncate">{{ $person->role }}</span>
                </span>
            @endif

            @if($person->department)
                <span class="q-chip">
                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#grid"></use></svg>
                    <span class="text-truncate">{{ $person->department }}</span>
                </span>
            @endif
        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="personOverviewDropdown-{{ $person->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="personOverviewDropdown-{{ $person->id }}">
            @can('update', $person)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('people.edit', $person) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('email', $person)
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $person)
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @can('delete', $person)
                <form action="{{ route('people.destroy', ['person' => $person, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                        <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use></svg>
                        Entfernen
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>
