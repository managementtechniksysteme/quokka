<div class="lead text-muted d-flex align-items-center">
    <svg class="icon icon-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#truck"></use>
    </svg>
    <a href="{{ route('vehicles.index') }}">Fuhrpark</a>
    <span class="px-2">/</span>
    <a href="{{ route('vehicles.show', $vehicle) }}">{{ $vehicle->registration_identifier }}</a>
</div>
