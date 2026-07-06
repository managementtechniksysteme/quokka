<nav class="q-breadcrumb">
    <a href="{{ route('companies.show', $additionsReport->project->company) }}">{{ $additionsReport->project->company->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <a href="{{ route('projects.show', $additionsReport->project) }}">{{ $additionsReport->project->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <span>Regiebericht #{{ $additionsReport->number }}</span>
</nav>
