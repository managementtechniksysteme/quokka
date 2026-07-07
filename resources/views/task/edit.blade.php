@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('task.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Aufgabe bearbeiten</div>
                    <h1 class="q-title">{{ $task->name }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" enctype="multipart/form-data" action="{{ route('tasks.update', $task) }}" method="post" novalidate>
            @method('PATCH')
            @include('task.fields', [ 'task' => $task, 'currentProject' => $currentProject, 'projects' => $projects, 'currentResponsibleEmployee' => $currentResponsibleEmployee, 'currentInvolvedEmployees' => $currentInvolvedEmployees, 'employees' => $employees, 'currentAttachments' => $currentAttachments ])

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('tasks.show', $task) }}"><svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use></svg>
                    Aufgabe speichern
                </button>
            </div>
        </form>
    </div>
@endsection
