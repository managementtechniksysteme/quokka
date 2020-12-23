<div class="lead text-muted d-flex align-items-center">
    <svg class="feather feather-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
    </svg>
    <a href="{{ route('projects.index') }}">Projekte</a>
    <span class="px-2">/</span>
    <a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
</div>
