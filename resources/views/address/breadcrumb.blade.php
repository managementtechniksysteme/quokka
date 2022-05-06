<div class="lead text-muted d-flex align-items-center">
    <svg class="icon icon-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
    </svg>
    <a href="{{ route('addresses.index') }}">Adressen</a>
    <span class="px-2">/</span>
    <a href="{{ route('addresses.show', $address) }}">{{ $address->address_line }}</a>
</div>
