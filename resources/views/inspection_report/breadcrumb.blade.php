<nav class="q-breadcrumb">
    <a href="{{ route('companies.show', $inspectionReport->project->company) }}">{{ $inspectionReport->project->company->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <a href="{{ route('projects.show', $inspectionReport->project) }}">{{ $inspectionReport->project->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <span>Prüfbericht · Anlage {{ $inspectionReport->equipment_identifier }}</span>
</nav>
