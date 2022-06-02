@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon-bs icon-baseline mr-1">
                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                </svg>
                Regiebericht anlegen
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" enctype="multipart/form-data" action="{{ route('additions-reports.store') }}" method="post" novalidate>
            @component('additions_report.fields', [ 'additionsReport' => $additionsReport, 'currentProject' => $currentProject, 'projects' => $projects, 'currentInvolvedEmployees' => $currentInvolvedEmployees, 'employees' => $employees, 'currentPresentPeople' => $currentPresentPeople, 'people' => $people, 'currentAttachments' => $currentAttachments, 'minAccountingAmount' => $minAccountingAmount ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Regiebericht speichern
            </button>
        </form>
    </div>
@endsection
