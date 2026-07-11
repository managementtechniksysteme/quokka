@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('additions_report.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Regiebericht bearbeiten</div>
                    <h1 class="q-title">{{ $currentProject->name }} #{{ $additionsReport->number }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" enctype="multipart/form-data" action="{{ route('additions-reports.update', $additionsReport) }}" method="post" novalidate>
            @method('PATCH')
            @include('additions_report.fields', ['additionsReport' => $additionsReport, 'currentProject' => $currentProject, 'projects' => $projects, 'currentInvolvedEmployees' => $currentInvolvedEmployees, 'employees' => $employees, 'currentPresentPeople' => $currentPresentPeople, 'people' => $people, 'currentAttachments' => $currentAttachments, 'minAccountingAmount' => $minAccountingAmount])

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('additions-reports.show', $additionsReport) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    Regiebericht speichern
                </button>
            </div>
        </form>
    </div>
@endsection
