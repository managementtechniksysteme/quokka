@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('task.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chat-dots"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Kommentar bearbeiten</div>
                    <h1 class="q-title">{{ $task->name }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" enctype="multipart/form-data" action="{{ route('comments.update', $comment) }}" method="post" novalidate>
            @method('PATCH')
            @include('comment.fields', ['task' => $task, 'comment' => $comment, 'currentAttachments' => $currentAttachments])

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('tasks.show', $task) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#save"></use></svg>
                    Kommentar speichern
                </button>
            </div>
        </form>
    </div>
@endsection
