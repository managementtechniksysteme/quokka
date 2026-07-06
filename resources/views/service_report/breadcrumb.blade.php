<nav class="q-breadcrumb">
    <a href="{{ route('companies.show', $serviceReport->project->company) }}">{{ $serviceReport->project->company->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <a href="{{ route('projects.show', $serviceReport->project) }}">{{ $serviceReport->project->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <span>Servicebericht #{{ $serviceReport->number }}</span>
</nav>
