<nav class="q-breadcrumb">
    <a href="{{ route('companies.show', $flowMeterInspectionReport->project->company) }}">{{ $flowMeterInspectionReport->project->company->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <a href="{{ route('projects.show', $flowMeterInspectionReport->project) }}">{{ $flowMeterInspectionReport->project->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <span>Durchfluss-Prüfbericht · Anlage {{ $flowMeterInspectionReport->equipment_identifier }}</span>
</nav>
