<nav class="q-breadcrumb">
    <a href="{{ route('projects.index') }}">Projekte</a>
    <span class="q-breadcrumb__sep">/</span>
    <a href="{{ route('projects.show', $interimInvoice->project) }}">{{ $interimInvoice->project->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <span>{{ $interimInvoice->title }}</span>
</nav>
