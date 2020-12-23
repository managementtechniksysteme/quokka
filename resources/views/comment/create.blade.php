@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('task.breadcrumb')

            <h3>
                Kommentar anlegen
                <small>Aufgabe {{ $task->name }}</small>
            </h3>
        </div>
    </div>

    <div class="container mt-4">
        <form class="needs-validation mt-4" action="{{ route('comments.store') }}" method="post" novalidate>
            @component('comment.fields', [ 'task' => $task, 'comment' => $comment ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Kommentar speichern
            </button>
        </form>
    </div>
@endsection
