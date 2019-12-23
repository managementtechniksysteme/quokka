<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('tasks.show', $task) }}"></a>
            <p class="m-0">
                {{ $task->name }}
            </p>
            <p class="text-muted d-inline-flex align-items-center m-0">
                @if(isset($secondaryInformation))
                    @switch($secondaryInformation)
                        @case('withoutProject')
                            <svg class="feather feather-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                            </svg>
                            {{ $task->responsibleEmployee->person->name }}
                            <svg class="@if($task->isOverdue()) text-danger @elseif($task->isDueSoon()) text-warning @else text-muted @endif feather feather-16 ml-2 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                            </svg>
                            {{ $task->due_on ? $task->due_on->format('d.m.Y') : 'kein Fälligkeitsdatum angegeben' }}
                            @break
                        @default
                            <svg class="feather feather-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                            </svg>
                            {{ $task->project->name }}
                            <svg class="feather feather-16 ml-2 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                            </svg>
                            {{ $task->responsibleEmployee->person->name }}
                            <svg class="@if($task->isOverdue()) text-danger @elseif($task->isDueSoon()) text-warning @else text-muted @endif feather feather-16 ml-2 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                            </svg>
                            {{ $task->due_on ? $task->due_on->format('d.m.Y') : 'kein Fälligkeitsdatum angegeben' }}
                            @break
                    @endswitch()
                @else
                    <svg class="feather feather-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                    </svg>
                    {{ $task->project->name }}
                    <svg class="feather feather-16 ml-2 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                    </svg>
                    {{ $task->responsibleEmployee->person->name }}
                    <svg class="@if($task->isOverdue()) text-danger @elseif($task->isDueSoon()) text-warning @else text-muted @endif feather feather-16 ml-2 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                    </svg>
                    {{ $task->due_on ? $task->due_on->format('d.m.Y') : 'kein Fälligkeitsdatum angegeben' }}
                @endif
            </p>
        </div>

        @if($task->private)
            <div class="col-auto text-right">
                <div class="text-warning d-inline-flex align-items-center">
                    <svg class="feather feather-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#lock"></use>
                    </svg>
                    <span class="text-muted">privat</span>
                </div>
            </div>
        @endif


        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="companyOverviewDropdown" data-toggle="dropdown"></button>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.edit', $task) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Aufgabe bearbeiten
                    </a>

                    <form action="{{ route('tasks.destroy', $task) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Aufgabe entfernen
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
