<div class="lead text-muted d-flex align-items-center">
    <svg class="icon-bs icon-16 mr-2">
        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
    </svg>
    <a href="{{ route('flow-meter-inspection-reports.index') }}">Prüfberichte für Durchflussmesseinrichtungen</a>
    <span class="px-2">/</span>
    <a href="{{ route('flow-meter-inspection-reports.show', $flowMeterInspectionReport) }}">Anlage {{ $flowMeterInspectionReport->equipment_identifier }} (Projekt {{ $flowMeterInspectionReport->project->name }}) vom {{ $flowMeterInspectionReport->inspected_on }}</a>
</div>
