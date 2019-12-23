@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="mt-4">
            <h3>
                Kommentar bearbeiten
                <small class="text-muted">{{ $task->name }}</small>
            </h3>

            <form class="needs-validation mt-4" action="{{ route('comments.update', $comment) }}" method="post" novalidate>
                @method('PATCH')
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
    </div>
@endsection
