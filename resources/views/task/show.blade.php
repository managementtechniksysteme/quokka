@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('task.breadcrumb')

            <h3>
                Aufgabe
                <small class="text-muted">{{ $task->name }}</small>
            </h3>
        </div>
    </div>

    <div class="container mt-4">
        <a class="btn btn-primary d-inline-flex align-items-center" href="{{ route('tasks.edit', $task) }}">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
            </svg>
            Aufgabe bearbeiten
        </a>
        <a class="btn btn-outline-warning d-none d-lg-inline-flex align-items-center" href="#">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
            </svg>
            Aufgabe zu Favoriten hinzufügen
        </a>
        <a class="btn btn-outline-secondary d-none d-lg-inline-flex align-items-center" href="#">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
            </svg>
            PDF der Aufgabe erstellen
        </a>
        <form action="{{ route('tasks.destroy', $task) }}" method="post" class="d-none d-lg-inline">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                </svg>
                Aufgabe entfernen
            </button>
        </form>

        <div class="dropdown d-inline d-lg-none">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownActionsButton" data-toggle="dropdown">
                Weitere Aktionen
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Aufgabe zu Favoriten hinzufügen
                </a>
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                    </svg>
                    PDF der Aufgabe erstellen
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

        @include ('task.show_overview')

        <div class="mt-4">
            <h4>Diskussion</h4>

            @unless($task->comments->isEmpty())
                <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('comments.create', ['task' => $task->id]) }}">
                    <svg class="feather feather-20 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                    </svg>
                    Kommentar anlegen
                </a>
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
