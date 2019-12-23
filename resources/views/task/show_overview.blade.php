<div class="mt-4">
    <div class="row">

        <div class="col-auto">

            <div class="row">

                <div class="col-auto">
                    <p class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                        </svg>
                        Projekt
                    </p>
                    <p class="text-muted d-flex align-items-center">
                        <svg class="@if($task->isOverdue()) text-danger @elseif($task->isDueSoon()) text-warning @else text-muted @endif feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                        </svg>
                        Fälligkeitsdatum
                    </p>
                    <p class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                        </svg>
                        Zeitraum
                    </p>
                    <p class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                        </svg>
                        Priorität
                    </p>
                    <p class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#git-commit"></use>
                        </svg>
                        Status
                    </p>
                    <p class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                        </svg>
                        Verrechnungsstatus
                    </p>
                    <p class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#lock"></use>
                        </svg>
                        Sichtbarkeitsstatus
                    </p>
                </div>

                <div class="col-auto">
                    <p>
                        {{ $task->project->name }}
                    </p>
                    <p>
                        {{ $task->due_on ? $task->due_on->format('d.m.Y') : 'nicht angegeben' }}
                    </p>
                    <p>
                        {{ $task->starts_on ? $task->starts_on->format('d.m.Y') : 'kein Start angegeben' }}
                        <svg class="feather feather-16 mx-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                        </svg>
                        {{ $task->ends_on ? $task->ends_on->format('d.m.Y') : 'kein Ende angegeben' }}
                    </p>
                    <p>
                        {{ __($task->priority) }}
                    </p>
                    <p>
                        {{ __($task->status) }}
                    </p>
                    <p>
                        {{ __($task->billed_string) }}
                    </p>
                    <p>
                        {{ __($task->visibility_string) }}
                    </p>
                </div>

            </div>

        </div>

        <div class="col-auto">
            <div class="row">

                <div class="col-auto">
                    <p class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user-check"></use>
                        </svg>
                        Verantwortlicher Mitarbeiter
                    </p>
                    <p class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                        </svg>
                        Beteiligte Mitarbeiter
                    </p>
                </div>

                <div class="col-auto">
                    <p>
                        {{ $task->responsibleEmployee->person->name }}
                    </p>
                    <p>
                        @foreach($task->involvedEmployees as $employee)
                            {{ $employee->person->name }}<br />
                        @endforeach
                    </p>
                </div>

            </div>
        </div>

    </div>


    <div class="row mt-2">
        <div class="col">
            <p class="text-muted d-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                </svg>
                Bemerkungen
            </p>
            <p>
                @if ($task->comment)
                    @markdown ($task->comment)
                @else
                    keine Bemerkungen angegeben
                @endif
            </p>
        </div>
    </div>
</div>

