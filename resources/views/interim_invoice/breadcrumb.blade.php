<div class="lead text-muted d-flex align-items-center">
    <svg class="icon icon-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
    </svg>
    <a href="{{ route('projects.index') }}">Projekte</a>
    <span class="px-2">/</span>
    <a href="{{ route('projects.show', $interimInvoice->project) }}">{{ $interimInvoice->project->name }}</a>
</div>
