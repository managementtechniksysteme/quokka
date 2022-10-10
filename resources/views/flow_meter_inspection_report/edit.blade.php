@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('flow_meter_inspection_report.breadcrumb')

            <h3>
                Prüfbericht für Durchflussmesseinrichtungen bearbeiten
                <small class="text-muted">Anlage {{ $flowMeterInspectionReport->equipment_identifier }} (Projekt {{ $currentProject->name }}) vom {{ $flowMeterInspectionReport->inspected_on }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" enctype="multipart/form-data" action="{{ route('flow-meter-inspection-reports.update', $flowMeterInspectionReport) }}" method="post" novalidate>
            @method('PATCH')
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
