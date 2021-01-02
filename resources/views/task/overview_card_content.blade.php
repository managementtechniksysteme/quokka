<div class="overview-card rounded border-status @if($task->isNew()) border-primary @elseif($task->isInProgress()) border-warning @else border-success @endif">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('tasks.show', $task) }}"></a>
            <div>
                {{ $task->name }}
            </div>
            <div class="text-muted">
                @if(isset($secondaryInformation))
                    @switch($secondaryInformation)
                        @case('withoutProject')
                            <div class="d-flex d-md-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                                </svg>
                                {{ $task->responsibleEmployee->person->name }}
                            </div>
                            <div class="d-flex d-md-inline-flex align-items-center">
                                <svg class="@if($task->isOverdue()) text-danger @elseif($task->isDueSoon()) text-warning @else text-muted @endif feather feather-16 ml-md-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                                </svg>
                                {{ $task->due_on ?? 'kein Datum angegeben' }}
                            </div>
                            @break
                        @default
                            <div class="d-flex d-lg-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                                </svg>
                                {{ $task->project->name }}
                            </div>
                            <div class="d-flex d-lg-inline-flex align-items-center">
                                <svg class="feather feather-16 ml-lg-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                                </svg>
                                {{ $task->responsibleEmployee->person->name }}
                                <svg class="@if($task->isOverdue()) text-danger @elseif($task->isDueSoon()) text-warning @else text-muted @endif feather feather-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                                </svg>
                                {{ $task->due_on ?? 'kein Datum angegeben' }}
                            </div>
                            @break
                    @endswitch()
                @else
                    <div class="d-flex d-md-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                        </svg>
                        {{ $task->project->name }}
                        </div>
                    <div class="d-flex d-md-inline-flex align-items-center">
                        <svg class="feather feather-16 ml-md-2 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                        </svg>
                        {{ $task->responsibleEmployee->person->name }}
                        <svg class="@if($task->isOverdue()) text-danger @elseif($task->isDueSoon()) text-warning @else text-muted @endif feather feather-16 ml-2 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                        </svg>
                        {{ $task->due_on ?? 'kein Datum angegeben' }}
                    </div>
                @endif
            </div>
        </div>

        @if($task->private)
            <div class="col-auto text-right">
                <div class="text-warning d-inline-flex align-items-center">
                    <svg class="feather feather-16 mr-md-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#lock"></use>
                    </svg>
                    <span class="text-muted d-none d-lg-inline-block">privat</span>
                </div>
            </div>
        @endif


        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="taskOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="taskOverviewDropdown">
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.edit', $task) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.email', $task) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email senden
                    </a>
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                        Favorisieren
                    </a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
