<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('companies.show', $company) }}"></a>

    <span class="q-avatar q-avatar--round q-avatar--{{ $company->avatar_colour }}">{{ $company->initials }}</span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $company->full_name }}</div>
        <div class="q-meta">
            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use></svg>
                {{ optional($company->address->first())->address_line ?? 'keine Adresse' }}
            </span>
            @can('viewAny', \App\Models\People::class)
                <span class="q-chip">
                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use></svg>
                    {{ $company->people_count }} {{ trans_choice('Person|Personen', $company->people_count) }}
                </span>
            @endcan
        </div>
    </div>

    @can('viewAny', \App\Models\Project::class)
        <div class="q-metric @if(!$company->projects_count) q-metric--faint @endif">
            <div class="q-metric__value">{{ $company->projects_count }}</div>
            <div class="q-metric__label">{{ trans_choice('Projekt|Projekte', $company->projects_count) }}</div>
        </div>
    @endcan

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="companyOverviewDropdown-{{ $company->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="companyOverviewDropdown-{{ $company->id }}">
            @can('update', $company)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('companies.edit', $company) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('email', $company)
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $company)
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @can('delete', $company)
                <form action="{{ route('companies.destroy', $company) }}" method="post">
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
