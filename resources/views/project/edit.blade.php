@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>
            Projekt bearbeiten
            <small class="text-muted">{{ $project->name }}</small>
        </h3>

        <form class="needs-validation mt-4" action="{{ route('projects.update', $project) }}" method="post" novalidate>
            @method('PATCH')
            @component('project.fields', [ 'project' => $project, 'currentCompany' => $currentCompany, 'companies' => $companies ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                </svg>
                Projekt bearbeiten
            </button>
        </form>
    </div>
@endsection
