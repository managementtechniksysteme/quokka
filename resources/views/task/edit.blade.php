@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>
            Aufgabe bearbeiten
            <small class="text-muted">{{ $task->name }}</small>
        </h3>

        <form class="needs-validation mt-4" action="{{ route('tasks.update', $task) }}" method="post" novalidate>
            @method('PATCH')
            @component('task.fields', [ 'task' => $task, 'currentProject' => $currentProject, 'projects' => $projects, 'currentResponsibleEmployee' => $currentResponsibleEmployee, 'currentInvolvedEmployees' => $currentInvolvedEmployees, 'employees' => $employees ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Aufgabe speichern
            </button>
        </form>
    </div>
@endsection