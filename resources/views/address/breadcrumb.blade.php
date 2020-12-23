<div class="lead">
    <a href="{{ route('addresses.index') }}">Adressen</a> <span class="text-muted">/</span> <a href="{{ route('addresses.show', $address) }}">{{ $address->address_line }}</a>
</div>
