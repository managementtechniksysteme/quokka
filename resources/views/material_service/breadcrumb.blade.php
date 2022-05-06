<div class="lead text-muted d-flex align-items-center">
    <svg class="icon icon-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#box"></use>
    </svg>
    <a href="{{ route('material-services.index') }}">Materialleistungen</a>
    <span class="px-2">/</span>
    <a href="{{ route('material-services.show', $materialService) }}">{{ $materialService->name }}</a>
</div>
