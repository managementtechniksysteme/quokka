<div class="lead">
    <a href="{{ route('service-reports.index') }}">Serviceberichte</a> <span class="text-muted">/</span> <a href="{{ route('service-reports.show', $serviceReport) }}">{{ $serviceReport->project->name }} #{{ $serviceReport->number }}</a>
</div>
