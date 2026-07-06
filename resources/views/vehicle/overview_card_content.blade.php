<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('vehicles.show', $vehicle) }}"></a>

    <span class="q-avatar">
        <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#truck"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $vehicle->registration_identifier }}@if($vehicle->make_model) <span class="q-row__sub">· {{ $vehicle->make_model }}</span>@endif</div>
        @if($vehicle->private || $vehicle->current_kilometres)
            <div class="q-meta">
                @if($vehicle->private)
                    <span class="q-chip">
                        <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#lock"></use></svg>
                        privat
                    </span>
                @endif
                @if($vehicle->current_kilometres)
                    <span class="q-chip">
                        <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#database"></use></svg>
                        {{ $vehicle->current_kilometres_string }}
                    </span>
                @endif
            </div>
        @endif
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="vehicleOverviewDropdown-{{ $vehicle->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="vehicleOverviewDropdown-{{ $vehicle->id }}">
            @can('update', $vehicle)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('vehicles.edit', $vehicle) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('email', $vehicle)
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $vehicle)
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @can('delete', $vehicle)
                <form action="{{ route('vehicles.destroy', $vehicle) }}" method="post">
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
