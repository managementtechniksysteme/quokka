<div class="lead text-muted d-flex align-items-center">
    <svg class="icon icon-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-circle"></use>
    </svg>
    <a href="{{ route('exceptions.index') }}">Fehlerdateien</a>
    <span class="px-2">/</span>
    <a href="{{ route('exceptions.show', $exception['uuid']) }}">{{ $exception['uuid'] }}</a>
</div>
