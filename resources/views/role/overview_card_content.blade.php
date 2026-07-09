<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('roles.show', $role) }}"></a>

    <span class="q-avatar">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#key"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $role->name }}</div>
    </div>

    <div class="q-metric @if(!$role->permissions_count) q-metric--faint @endif">
        <div class="q-metric__value">{{ $role->permissions_count }}</div>
        <div class="q-metric__label">{{ trans_choice('Berechtigung|Berechtigungen', $role->permissions_count) }}</div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="roleOverviewDropdown-{{ $role->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="roleOverviewDropdown-{{ $role->id }}">
            @can('update', $role)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('roles.edit', $role) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('create', \Spatie\Permission\Models\Role::class)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('roles.create', ['template' => $role]) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#files"></use></svg>
                    Kopieren
                </a>
            @endcan
            @can('email', $role)
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $role)
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @can('delete', $role)
                <form action="{{ route('roles.destroy', $role) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                        <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                        Entfernen
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>
