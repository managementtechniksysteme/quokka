<nav class="q-breadcrumb">
    <a href="{{ route('companies.show', $deliveryNote->project->company) }}">{{ $deliveryNote->project->company->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <a href="{{ route('projects.show', $deliveryNote->project) }}">{{ $deliveryNote->project->name }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <span>Lieferschein «{{ $deliveryNote->title }}»</span>
</nav>
