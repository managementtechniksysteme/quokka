<nav class="q-breadcrumb">
    <a href="{{ route('companies.show', $constructionReport->project->company) }}">{{ $constructionReport->project->company->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <a href="{{ route('projects.show', $constructionReport->project) }}">{{ $constructionReport->project->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <span>Bautagesbericht #{{ $constructionReport->number }}</span>
</nav>
