@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('inspection_report.breadcrumb')

            <h3>
                Prüfbericht bearbeiten
                <small class="text-muted">Anlage {{ $inspectionReport->equipment_identifier }} (Projekt {{ $currentProject->name }}) vom {{ $inspectionReport->inspected_on }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" enctype="multipart/form-data" action="{{ route('inspection-reports.update', $inspectionReport) }}" method="post" novalidate>
            @method('PATCH')
            @component('inspection_report.fields', [ 'inspectionReport' => $inspectionReport, 'currentProject' => $currentProject, 'projects' => $projects, 'currentAttachments' => $currentAttachments ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Prüfbericht speichern
            </button>
        </form>
    </div>
@endsection
