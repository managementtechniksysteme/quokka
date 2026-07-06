<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('wage-services.show', $wageService) }}"></a>

    <span class="q-avatar">
        <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cpu"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $wageService->name }}</div>
        <div class="q-meta">
            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#circle"></use></svg>
                {{ $wageService->unit }}
            </span>
            @if($wageService->description)
                <span class="q-chip">
                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use></svg>
                    <span class="text-truncate">{{ $wageService->description }}</span>
                </span>
            @endif
        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="wageServiceOverviewDropdown-{{ $wageService->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="wageServiceOverviewDropdown-{{ $wageService->id }}">
            @can('update', $wageService)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('wage-services.edit', $wageService) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('email', $wageService)
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $wageService)
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @can('delete', $wageService)
                <form action="{{ route('wage-services.destroy', $wageService) }}" method="post">
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
