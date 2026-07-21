<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('projects.show', $project) }}"></a>

    <span class="q-avatar">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">
            {{ $project->name }}@if(($secondaryInformation ?? '') !== 'dates') <span class="q-row__sub d-none d-md-inline">· {{ $project->company->name }}</span>@endif
        </div>

        {{-- Mobile: company doesn't fit the title's text-truncate as a
             trailing "· Name" suffix (2026-07-21, user: "cut off most of the
             time") — isolate it as its own truncated chip line, same
             pattern as the project chip on inspection/flow-meter reports. --}}
        @if(($secondaryInformation ?? '') !== 'dates')
            <div class="q-meta mb-1 d-md-none">
                <span class="q-chip">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#briefcase"></use></svg>
                    <span class="text-truncate">{{ $project->company->name }}</span>
                </span>
            </div>
        @endif

        <div class="q-meta">
            <span class="q-status q-status--{{ $project->state }}">{{ $project->state_label }}</span>

            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                {{ optional($project->starts_on)->format('d.m.Y') ?? 'kein Start' }}
                @if($project->state === 'finished' && $project->ends_on)
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-right"></use></svg>
                    {{ $project->ends_on->format('d.m.Y') }}
                @endif
            </span>

            {{-- Cost indicator chip: desktop only. It's dense shorthand
                 (G/V/L/M + trend arrows) that needs explaining once you
                 already know the app — not worth the space on mobile. --}}
            @if(Auth::user()->can('projects.view.estimates') && Auth::user()->settings->show_cost_estimates)
                @if($project->current_wage_costs_status || $project->current_material_costs_status || $project->current_costs_status || $project->current_billed_costs_status)
                    <span class="q-chip d-none d-md-inline-flex">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
                        @if($project->current_costs_status)
                            <span>G</span>
                            <svg class="icon-bs icon-12 text-{{ $project->current_costs_status }}">
                                @switch($project->current_costs_status)
                                    @case('success')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use>@break
                                    @case('warning')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down-right"></use>@break
                                    @case('danger')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use>@break
                                @endswitch
                            </svg>
                        @endif
                        @if($project->current_billed_costs_status)
                            <span>V</span>
                            <svg class="icon-bs icon-12 text-{{ $project->current_billed_costs_status }}">
                                @switch($project->current_billed_costs_status)
                                    @case('success')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use>@break
                                    @case('warning')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down-right"></use>@break
                                    @case('danger')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use>@break
                                @endswitch
                            </svg>
                        @endif
                        @if($project->current_wage_costs_status)
                            <span>L</span>
                            <svg class="icon-bs icon-12 text-{{ $project->current_wage_costs_status }}">
                                @switch($project->current_wage_costs_status)
                                    @case('success')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use>@break
                                    @case('warning')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down-right"></use>@break
                                    @case('danger')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use>@break
                                @endswitch
                            </svg>
                        @endif
                        @if($project->current_material_costs_status)
                            <span>M</span>
                            <svg class="icon-bs icon-12 text-{{ $project->current_material_costs_status }}">
                                @switch($project->current_material_costs_status)
                                    @case('success')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use>@break
                                    @case('warning')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down-right"></use>@break
                                    @case('danger')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use>@break
                                @endswitch
                            </svg>
                        @endif
                    </span>
                @endif
            @endif
        </div>
    </div>

    <div class="q-metric @if(!$project->tasks_count) q-metric--faint @endif">
        <div class="q-metric__value">{{ $project->tasks_count }}</div>
        <div class="q-metric__label">{{ trans_choice('Aufgabe|Aufgaben', $project->tasks_count) }}</div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="projectOverviewDropdown-{{ $project->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="projectOverviewDropdown-{{ $project->id }}">
            @can('update', $project)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('projects.edit', $project) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('email', $project)
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $project)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('projects.download', $project) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @can('delete', $project)
                <form action="{{ route('projects.destroy', ['project' => $project, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
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

    <svg class="icon-bs icon-16 q-row__chevron d-md-none"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-right"></use></svg>
</div>
