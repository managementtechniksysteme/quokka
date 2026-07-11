@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('inspection_report.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Prüfbericht bearbeiten</div>
                    <h1 class="q-title">Anlage {{ $inspectionReport->equipment_identifier }} (Projekt {{ $currentProject->name }}) vom {{ $inspectionReport->inspected_on }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" enctype="multipart/form-data" action="{{ route('inspection-reports.update', $inspectionReport) }}" method="post" novalidate>
            @method('PATCH')
            @include('inspection_report.fields', ['inspectionReport' => $inspectionReport, 'currentProject' => $currentProject, 'projects' => $projects, 'currentAttachments' => $currentAttachments])

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('inspection-reports.show', $inspectionReport) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    Prüfbericht speichern
                </button>
            </div>
        </form>
    </div>
@endsection
