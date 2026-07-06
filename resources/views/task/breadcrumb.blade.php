<nav class="q-breadcrumb">
    <a href="{{ route('companies.show', $task->project->company) }}">{{ $task->project->company->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <a href="{{ route('projects.show', $task->project) }}">{{ $task->project->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <span>{{ $task->name }}</span>
</nav>
