<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('tasks.show', $task) }}"></a>

    <span class="q-avatar">
        <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $task->name }}</div>
        <div class="q-meta">
            <span class="q-status q-status--{{ Str::slug($task->status) }}">{{ $task->status_label }}</span>

            @if($task->private)
                <span class="q-chip">
                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#lock"></use></svg>
                    privat
                </span>
            @endif

            @unless(isset($secondaryInformation) && $secondaryInformation === 'withoutProject')
                <span class="q-chip">
                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use></svg>
                    <span class="text-truncate">{{ $task->project->name }}</span>
                </span>
            @endunless

            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use></svg>
                <span class="text-truncate">{{ $task->responsibleEmployee->person->name }}</span>
            </span>

            @if($task->due_on)
                <span class="q-chip @if($task->isOverdue()) q-chip--danger @elseif($task->isDueSoon()) q-chip--warning @endif">
                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use></svg>
                    {{ $task->due_on }}
                </span>
            @endif
        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="taskOverviewDropdown-{{ $task->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="taskOverviewDropdown-{{ $task->id }}">
            @unless($task->status === 'finished')
                @can('update', $task)
                    <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('tasks.finish', ['task' => $task, 'redirect' => $actionRedirect ?? 'index']) }}">
                        <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use></svg>
                        Erledigen
                    </a>
                @endcan
            @endunless
            @can('update', $task)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.edit', $task) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('create', \App\Models\Task::class)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.create', ['template' => $task]) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#copy"></use></svg>
                    Kopieren
                </a>
            @endcan
            @can('email', $task)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.email', ['task' => $task, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $task)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.download', $task) }}" target="_blank">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @can('delete', $task)
                <form action="{{ route('tasks.destroy', ['task' => $task, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
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
