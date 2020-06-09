<div class="mt-4">

    <div class="row">
        <div class="col-sm-5 col-md-4 col-lg-2">
            <div class="text-muted d-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                </svg>
                Projekt
            </div>
        </div>
        <div class="col-sm-7">
            {{ $task->project->name }}
        </div>
    </div>

    <div class="row mt-3 mt-md-4">
        <div class="col-md-8 col-lg">
            <div class="row">
                <div class="col-sm-5 col-md col-lg-4">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="@if($task->isOverdue()) text-danger @elseif($task->isDueSoon()) text-warning @else text-muted @endif feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                        </svg>
                        Fälligkeitsdatum
                    </div>
                </div>
                <div class="col-sm-7 col-md col-lg-8">
                    {{ $task->due_on ? $task->due_on->format('d.m.Y') : 'nicht angegeben' }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-5 col-md col-lg-4">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                        </svg>
                        Zeitraum
                    </div>
                </div>
                <div class="col-sm-7 col-md col-lg-8">
                    {{ $task->starts_on ? $task->starts_on->format('d.m.Y') : 'kein Start' }}
                    <svg class="feather feather-16 mx-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                    </svg>
                    {{ $task->ends_on ? $task->ends_on->format('d.m.Y') : 'kein Ende' }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-5 col-md col-lg-4">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                        </svg>
                        Priorität
                    </div>
                </div>
                <div class="col-sm-7 col-md col-lg-8">
                    {{ __($task->priority) }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-5 col-md col-lg-4">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="@if($task->isNew()) text-primary @elseif($task->isInProgress()) text-warning @else text-success @endif feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#git-commit"></use>
                        </svg>
                        Status
                    </div>
                </div>
                <div class="col-sm-7 col-md col-lg-8">
                    {{ __($task->status) }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-5 col-md col-lg-4">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                        </svg>
                        Verrechnungsstatus
                    </div>
                </div>
                <div class="col-sm-7 col-md col-lg-8">
                    {{ __($task->billed_string) }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-5 col-md col-lg-4">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="@if($task->private) text-warning @else text-muted @endif feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#lock"></use>
                        </svg>
                        Sichtbarkeitsstatus
                    </div>
                </div>
                <div class="col-sm-7 col-md col-lg-8">
                    {{ __($task->visibility_string) }}
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg">
            <div class="row mt-3 mt-md-0">
                <div class="col-sm-5 col-md-12 col-lg">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                        </svg>
                        Verantwortliche Person
                    </div>
                </div>
                <div class="col-sm-7 col-md-12 col-lg">
                    {{ $task->responsibleEmployee->person->name }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-5 col-md-12 col-lg">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                        </svg>
                        Mitwirkende Personen
                    </div>
                </div>
                <div class="col-sm-7 col-md-12 col-lg">
                    @forelse($task->involvedEmployees as $employee)
                        {{ $employee->person->name }}
                        @unless($loop->last)
                            <br />
                        @endunless
                    @empty
                        keine Personen angegeben
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="text-muted d-flex align-items-center mt-4">
        <svg class="feather feather-16 mr-2">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
        </svg>
        Bemerkungen
    </div>
    @if ($task->comment)
        @markdown ($task->comment)
    @else
        keine Bemerkungen angegeben
    @endif

</div>

