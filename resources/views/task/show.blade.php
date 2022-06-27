@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('task.breadcrumb')

            <h3>
                Aufgabe
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $task->name }}
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @unless($task->status === 'finished')
                    @can('update', $task)
                        <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('tasks.finish', ['task' => $task, 'redirect' => 'show']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            Erledigen
                        </a>
                    @endcan
                @endunless
                @can('update', $task)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('tasks.edit', $task) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('create', \App\Models\Task::class)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('tasks.create', ['template' => $task]) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#copy"></use>
                        </svg>
                        Kopieren
                    </a>
                @endcan
                @can('email', $task)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('tasks.email', ['task' => $task, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $task)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('tasks.download', $task) }}" target="_blank">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                @endcan
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                @can('delete', $task)
                    <form action="{{ route('tasks.destroy', $task) }}" method="post" >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
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

    <div class="container my-4">
        @include ('task.show_overview')

        <div class="mt-4">
            <h4>
                Aktivitäten und Diskussion
                @unless($activities->isEmpty())
                    <small class="text-muted">{{ trans_choice('messages.entries', $activities->total()) }}</small>
                @endunless
            </h4>

            @unless($activities->isEmpty())
                <div class="mb-2">
                    @can('create', [\App\Models\TaskComment::class, $task])
                        <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('comments.create', ['task' => $task->id]) }}">
                            <svg class="icon icon-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                            </svg>
                            Kommentar anlegen
                        </a>
                    @endcan
                </div>
            @endunless

            <div class="mt-3">
                @forelse ($activities as $activity)
                    @switch(get_class($activity))
                        @case(\Spatie\Activitylog\Models\Activity::class)
                            @component('task.activity_card', [ 'activity' => $activity ])
                            @endcomponent
                            @break
                        @case(\App\Models\TaskComment::class)
                            @component('comment.overview_card', [ 'comment' => $activity ])
                            @endcomponent
                            @break
                    @endswitch
                @empty
                    <div class="text-center">
                        <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                        <p class="lead text-muted">Zu der Aufgabe {{ $task->name }} gibt es noch keine Aktivitäten.</p>
                        @can('create', [\App\Models\TaskComment::class, $task])
                            <p class="lead">Lege einen neuen Kommentar an.</p>
                            <a class="btn btn-lg btn-primary d-inline-flex align-items-center" href="{{ route('comments.create', ['task' => $task->id]) }}">
                                <svg class="icon icon-20 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                                </svg>
                                Kommentar anlegen
                            </a>
                        @endcan
                    </div>
                @endforelse
            </div>

            <div class="mt-2">
                {{ $activities->links() }}
            </div>

        </div>
    </div>
@endsection
