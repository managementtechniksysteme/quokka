<div class="lead text-muted d-flex align-items-center">
    <svg class="feather feather-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
    </svg>
    <a href="{{ route('service-reports.index') }}">Serviceberichte</a>
    <span class="px-2">/</span>
    <a href="{{ route('service-reports.show', $serviceReport) }}">{{ $serviceReport->project->name }} #{{ $serviceReport->number }}</a>
</div>
