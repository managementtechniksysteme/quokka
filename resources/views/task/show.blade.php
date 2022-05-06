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
                Diskussion
                @if($comments)
                    <small class="text-muted">{{ $comments->total() }} Einträge</small>
                @endif
            </h4>

            @unless($comments->isEmpty())
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
                @forelse ($comments as $comment)
                    @component('comment.overview_card', [ 'comment' => $comment ])
                    @endcomponent
                @empty
                    <div class="text-center">
                        <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                        <p class="lead text-muted">Zu der Aufgabe {{ $task->name }} gibt es noch keine Diskussion.</p>
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
                {{ $comments->links() }}
            </div>

        </div>
    </div>
@endsection
