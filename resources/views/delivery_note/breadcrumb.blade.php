<div class="lead text-muted d-flex align-items-center">
    <svg class="icon icon-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#package"></use>
    </svg>
    <a href="{{ route('delivery-notes.index') }}">Lieferscheine</a>
    <span class="px-2">/</span>
    <a href="{{ route('delivery-notes.show', $deliveryNote) }}">{{ $deliveryNote->title }}</a>
</div>
