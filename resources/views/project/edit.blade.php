@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('project.breadcrumb')

            <h3>
                Projekt bearbeiten
                <small class="text-muted">{{ $project->name }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('projects.update', $project) }}" method="post" novalidate>
            @method('PATCH')
            @component('project.fields', [ 'project' => $project, 'currencyUnit' => $currencyUnit, 'currentCompany' => $currentCompany, 'companies' => $companies ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Projekt speichern
            </button>
        </form>
    </div>
@endsection
