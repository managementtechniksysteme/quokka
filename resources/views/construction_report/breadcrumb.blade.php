<div class="lead text-muted d-flex align-items-center">
    <svg class="icon-bs icon-16 mr-2">
        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
    </svg>
    <a href="{{ route('construction-reports.index') }}">Bautagesberichte</a>
    <span class="px-2">/</span>
    <a href="{{ route('construction-reports.show', $constructionReport) }}">{{ $constructionReport->project->name }} #{{ $constructionReport->number }}</a>
</div>
