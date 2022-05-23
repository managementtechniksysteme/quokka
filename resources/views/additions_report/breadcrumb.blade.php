<div class="lead text-muted d-flex align-items-center">
    <svg class="icon-bs icon-16 mr-2">
        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
    </svg>
    <a href="{{ route('additions-reports.index') }}">Regieberichte</a>
    <span class="px-2">/</span>
    <a href="{{ route('additions-reports.show', $additionsReport) }}">{{ $additionsReport->project->name }} #{{ $additionsReport->number }}</a>
</div>
