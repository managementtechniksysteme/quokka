@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('task.breadcrumb')

            <h3>
                Aufgabe bearbeiten
                <small class="text-muted">{{ $task->name }}</small>
            </h3>
        </div>
    </div>

    <div class="container mt-4">
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
