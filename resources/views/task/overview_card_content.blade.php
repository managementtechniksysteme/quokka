<div class="overview-card rounded border-status @if($task->isNew()) border-primary @elseif($task->isInProgress()) border-warning @else border-success @endif">
    <div class="mw-100 d-flex flex-grow-1 p-3 align-items-center">
        <div class="mw-100 d-flex flex-grow-1 h-100 align-items-center">

            <div class="mw-100 flex-grow-1 position-relative">
                <a class="stretched-link outline-none" href="{{ route('tasks.show', $task) }}"></a>

                <div class="mw-100 text-truncate">
                    {{ $task->name }}
                </div>
                <div class="mw-100 text-muted">
                    @if(isset($secondaryInformation))
                        @switch($secondaryInformation)
                            @case('withoutProject')
                                <div class="mw-100 d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-1">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                                    </svg>
                                    <span class="mw-100 text-truncate">{{ $task->responsibleEmployee->person->name }}</span>
                                </div>
                                <div class="@if($task->isOverdue()) bg-red-100 text-red-800 rounded px-1 @elseif($task->isDueSoon()) bg-yellow-100 text-yellow-800 rounded px-1 @else text-muted @endif ml-2 d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-1">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                                    </svg>
                                    {{ $task->due_on ?? 'kein Datum' }}
                                </div>
                                @break
                            @default
                                <div class="mw-100 d-flex d-md-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-1">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                                    </svg>
                                    <span class="mw-100 text-truncate">{{ $task->project->name }}</span>
                                </div>
                                <div class="d-flex d-md-inline-flex align-items-center">
                                    <svg class="icon icon-16 ml-md-2 mr-1">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                                    </svg>
                                    {{ $task->responsibleEmployee->person->name }}
                                    <div class="@if($task->isOverdue()) bg-red-100 text-red-800 rounded px-1 @elseif($task->isDueSoon()) bg-yellow-100 text-yellow-800 rounded px-1 @else text-muted @endif ml-2 d-inline-flex align-items-center">
                                        <svg class="icon icon-16 mr-1">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                                        </svg>
                                        {{ $task->due_on ?? 'kein Datum' }}
                                    </div>
                                </div>
                                @break
                        @endswitch()
                    @else
                        <div class="mw-100 d-flex d-md-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                            </svg>
                            <span class="mw-100 text-truncate">{{ $task->project->name }}</span>
                        </div>
                        <div class="d-flex d-md-inline-flex align-items-center">
                            <svg class="icon icon-16 ml-md-2 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                            </svg>
                            {{ $task->responsibleEmployee->person->name }}
                            <div class="@if($task->isOverdue()) bg-red-100 text-red-800 rounded px-1 @elseif($task->isDueSoon()) bg-yellow-100 text-yellow-800 rounded px-1 @else text-muted @endif ml-2 d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                                </svg>
                                {{ $task->due_on ?? 'kein Datum' }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($task->private)
                <div class="d-block ml-2">
                    <div class="text-warning d-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-md-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#lock"></use>
                        </svg>
                        <span class="text-muted d-none d-lg-inline-block">privat</span>
                    </div>
                </div>
            @endif
        </div>


        <div class="d-none d-md-block ml-2">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="taskOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="taskOverviewDropdown">
                    @unless($task->status === 'finished')
                        @can('update', $task)
                            <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('tasks.finish', ['task' => $task, 'redirect' => $actionRedirect ?? 'index']) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                                </svg>
                                Erledigen
                            </a>
                        @endcan
                    @endunless
                    @can('update', $task)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.edit', $task) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('create', \App\Models\Task::class)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.create', ['template' => $task]) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#copy"></use>
                            </svg>
                            Kopieren
                        </a>
                    @endcan
                    @can('email', $task)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.email', ['task' => $task, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $task)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.download', $task) }}" target="_blank">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                            </svg>
                            PDF erstellen
                        </a>
                    @endcan
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                        Favorisieren
                    </a>
                    @can('delete', $task)
                        <form action="{{ route('tasks.destroy', ['task' => $task, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                                </svg>
                                Entfernen
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>

    </div>
</div>
