<div class="lead text-muted d-flex align-items-center">
    <svg class="icon-bs icon-16 mr-2">
        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
    </svg>
    <a href="{{ route('inspection-reports.index') }}">Prüfberichte</a>
    <span class="px-2">/</span>
    <a href="{{ route('inspection-reports.show', $inspectionReport) }}">Anlage {{ $inspectionReport->equipment_identifier }} (Projekt {{ $inspectionReport->project->name }}) vom {{ $inspectionReport->inspected_on }}</a>
</div>
