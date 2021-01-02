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
                        <svg class="feather feather-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('tasks.edit', $task) }}">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                    </svg>
                    Bearbeiten
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('tasks.email', $task) }}">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                    </svg>
                    Email versenden
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                    </svg>
                    PDF erstellen
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                <form action="{{ route('tasks.destroy', $task) }}" method="post" >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                        </svg>
                        Entfernen
                    </button>
                </form>
            </div>

        </div>
    </div>

    <div class="container my-4">
        @include ('task.show_overview')

        <div class="mt-4">
            <h4>Diskussion</h4>

            @unless($task->comments->isEmpty())
                <div class="mb-2">
                    <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('comments.create', ['task' => $task->id]) }}">
                        <svg class="feather feather-20 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Kommentar anlegen
                    </a>
                </div>
            @endunless

            <div class="mt-3">
                @forelse ($task->comments as $comment)
                    @component('comment.overview_card', [ 'comment' => $comment ])
                    @endcomponent
                @empty
                    <div class="text-center">
                        <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                        <p class="lead text-muted">Zu der Aufgabe {{ $task->name }} gibt es noch keine Diskussion.</p>
                        <p class="lead">Lege einen neuen Kommentar an.</p>
                        <a class="btn btn-lg btn-primary d-inline-flex align-items-center" href="{{ route('comments.create', ['task' => $task->id]) }}">
                            <svg class="feather feather-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                            </svg>
                            Kommentar anlegen
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
