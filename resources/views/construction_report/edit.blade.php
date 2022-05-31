@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('construction_report.breadcrumb')

            <h3>
                Bautagesbericht bearbeiten
                <small class="text-muted">{{ $currentProject->name }} #{{ $constructionReport->number }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" enctype="multipart/form-data" action="{{ route('construction-reports.update', $constructionReport) }}" method="post" novalidate>
            @method('PATCH')
            @component('construction_report.fields', [ 'constructionReport' => $constructionReport, 'currentProject' => $currentProject, 'projects' => $projects, 'currentInvolvedEmployees' => $currentInvolvedEmployees, 'employees' => $employees, 'currentPresentPeople' => $currentPresentPeople, 'people' => $people, 'currentAttachments' => $currentAttachments, 'minAccountingAmount' => $minAccountingAmount ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Bautagesbericht speichern
            </button>
        </form>
    </div>
@endsection
