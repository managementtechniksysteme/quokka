<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('tasks.show', $task) }}"></a>

    <span class="q-avatar">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $task->name }}</div>

        {{-- Desktop: status + private + project + responsible + due-date,
             unchanged, all on one wrapping row. --}}
        <div class="q-meta d-none d-md-flex">
            <span class="q-status q-status--{{ Str::slug($task->status) }}">{{ $task->status_label }}</span>

            @if($task->private)
                <span class="q-chip">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#lock"></use></svg>
                    privat
                </span>
            @endif

            @unless(isset($secondaryInformation) && $secondaryInformation === 'withoutProject')
                <span class="q-chip">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                    <span class="text-truncate">{{ $task->project->name }}</span>
                </span>
            @endunless

            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#person"></use></svg>
                <span class="text-truncate">{{ $task->responsibleEmployee->person->name }}</span>
            </span>

            @if($task->due_on)
                <span class="q-chip @if($task->isOverdue()) q-chip--danger @elseif($task->isDueSoon()) q-chip--warning @endif">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                    {{ $task->due_on }}
                </span>
            @endif
        </div>

        {{-- Mobile: pared down (2026-07-21, user: "what's really relevant?
             ...anything more is visually too cluttered") — project alone on
             its own truncated line, then status + private + due-date on a
             second line. Responsible drops entirely here; still on the
             detail page one tap away, this is a list-scan density call, not
             a data removal. --}}
        <div class="d-md-none">
            @unless(isset($secondaryInformation) && $secondaryInformation === 'withoutProject')
                <div class="q-meta mb-1">
                    <span class="q-chip">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                        <span class="text-truncate">{{ $task->project->name }}</span>
                    </span>
                </div>
            @endunless
            <div class="q-meta">
                <span class="q-status q-status--{{ Str::slug($task->status) }}">{{ $task->status_label }}</span>
                @if($task->private)
                    {{-- Icon-only on mobile (2026-07-21, user: dropping the
                         text keeps row two reliably one line — with it,
                         status+"privat"+due-date could occasionally wrap to
                         a 3rd line on a narrow phone + long status label;
                         the lock glyph alone is already unambiguous).
                         aria-label carries the meaning for screen readers
                         since there's no visible text now. --}}
                    <span class="q-chip" aria-label="privat">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#lock"></use></svg>
                    </span>
                @endif
                @if($task->due_on)
                    <span class="q-chip @if($task->isOverdue()) q-chip--danger @elseif($task->isDueSoon()) q-chip--warning @endif">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                        {{ $task->due_on }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="taskOverviewDropdown-{{ $task->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="taskOverviewDropdown-{{ $task->id }}">
            @unless($task->status === 'finished')
                @can('update', $task)
                    <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('tasks.finish', ['task' => $task, 'redirect' => $actionRedirect ?? 'index']) }}">
                        <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                        Erledigen
                    </a>
                @endcan
            @endunless
            @can('update', $task)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.edit', $task) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('create', \App\Models\Task::class)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.create', ['template' => $task]) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#files"></use></svg>
                    Kopieren
                </a>
            @endcan
            @can('email', $task)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.email', ['task' => $task, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $task)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.download', $task) }}" target="_blank">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @can('delete', $task)
                <form action="{{ route('tasks.destroy', ['task' => $task, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
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
