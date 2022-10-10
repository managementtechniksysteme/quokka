@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon-bs icon-baseline mr-1">
                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                </svg>
                Prüfbericht für Durchflussmesseinrichtungen anlegen
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" enctype="multipart/form-data" action="{{ route('flow-meter-inspection-reports.store') }}" method="post" novalidate>
            @component('flow_meter_inspection_report.fields', [ 'flowMeterInspectionReport' => $flowMeterInspectionReport, 'comparison_measurement_q_percentages' => $comparison_measurement_q_percentages, 'currentProject' => $currentProject, 'projects' => $projects, 'currentAttachments' => $currentAttachments ])
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
