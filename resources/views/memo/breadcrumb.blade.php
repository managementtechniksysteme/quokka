<nav class="q-breadcrumb">
    <a href="{{ route('companies.show', $memo->project->company) }}">{{ $memo->project->company->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <a href="{{ route('projects.show', $memo->project) }}">{{ $memo->project->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <span>Aktenvermerk #{{ $memo->number }}</span>
</nav>
