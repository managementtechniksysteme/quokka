@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>Prüfbericht anlegen</h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" enctype="multipart/form-data" action="{{ route('inspection-reports.store') }}" method="post" novalidate>
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
