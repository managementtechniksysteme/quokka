<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('employees.show', $employee) }}"></a>

    {{-- avatar mirrors how the user appears in task comments etc.: their username
         string in their chosen colour, falling back to the person's hashed
         palette colour (initials) when there is no chosen colour / no account --}}
    <span class="q-avatar q-avatar--round @unless($employee->user?->avatar_colour_hex) q-avatar--{{ $employee->person->avatar_colour }} @endunless" @if($employee->user?->avatar_colour_hex) style="background: color-mix(in srgb, {{ $employee->user->avatar_colour_hex }} 20%, transparent); color: {{ $employee->user->avatar_colour_hex }};" @endif>{{ $employee->user ? $employee->user->username_avatar_string : $employee->person->initials }}</span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $employee->person->name }}</div>
        <div class="q-meta">
            @if($employee->user)
                <span class="q-chip @if($employee->user->trashed()) q-chip--warning @endif">
                    <svg class="icon icon-12">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#{{ $employee->user->trashed() ? 'user-x' : 'user' }}"></use>
                    </svg>
                    <span class="text-truncate">{{ $employee->user->username }}</span>
                </span>
            @endif

            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use></svg>
                {{ $employee->holidays }}
            </span>
        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="employeeOverviewDropdown-{{ $employee->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="employeeOverviewDropdown-{{ $employee->id }}">
            @can('update', $employee)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.edit', $employee) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('email', $employee)
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $employee)
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @if($employee->user && $employee->user->trashed())
                @can('update', $employee)
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.access-grant', $employee) }}">
                        <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#unlock"></use></svg>
                        Quokka Zugang entsperren
                    </a>
                @endcan
            @elseif($employee->user)
                @can('update', $employee)
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.access-deny', $employee) }}">
                        <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#lock"></use></svg>
                        Quokka Zugang sperren
                    </a>
                @endcan
                @can('impersonate', $employee)
                    @if(Session::has('impersonatorId') && Auth::id() === $employee->person_id)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.impersonate', $employee) }}">
                            <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user-minus"></use></svg>
                            Zurück zum eigenen Benutzer
                        </a>
                    @elseif(Auth::id() !== $employee->person_id)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.impersonate', $employee) }}">
                            <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user-plus"></use></svg>
                            Als Quokka Benutzer anmelden
                        </a>
                    @endif
                @endcan
            @endif
            @can('delete', $employee)
                <form action="{{ route('employees.destroy', $employee) }}" method="post">
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
