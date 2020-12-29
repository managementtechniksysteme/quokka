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
            <a href="{{ route('projects.show', $task->project) }}">{{ $task->project->name }}</a>
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
                    {{ $task->due_on ? $task->due_on : 'nicht angegeben' }}
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
                    {{ $task->starts_on ?? 'kein Start' }}
                    <svg class="feather feather-16 mx-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                    </svg>
                    {{ $task->ends_on ? $task->ends_on : 'kein Ende' }}
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

    @if ($task->comment)
        <div class="text-muted d-flex align-items-center mt-4">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Bemerkungen
        </div>
        @markdown ($task->comment)
    @endif

    @if($task->attachments()->count() > 0)
        <div class="text-muted d-flex align-items-center mt-4">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
            </svg>
            Anhänge
        </div>
        <div class="row">
            @foreach($task->attachments() as $attachment)
                <div class="col-12 col-md-6 col-lg-3 mt-1">
                    <div class="attachment bg-gray-100 border border-gray-300 d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                        <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                        <div class="min-w-0">
                            <div class="min-w-0 text-truncate">{{ $attachment->file_name }}</div>
                            <div class="text-muted">{{ $attachment->human_readable_size }}</div>
                        </div>
                        <a href="{{ $attachment->getUrl() }}" class="stretched-link outline-none"></a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

