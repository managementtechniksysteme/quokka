<div class="lead text-muted d-flex align-items-center">
    <svg class="icon icon-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cpu"></use>
    </svg>
    <a href="{{ route('wage-services.index') }}">Lohndienstleistungen</a>
    <span class="px-2">/</span>
    <a href="{{ route('wage-services.show', $wageService) }}">{{ $wageService->name }}</a>
</div>
