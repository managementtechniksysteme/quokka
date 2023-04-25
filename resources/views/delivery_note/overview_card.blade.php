<div class=" d-none d-md-block">
    @include ('delivery_note.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('delivery-notes.edit', $deliveryNote) }}">
    @include ('delivery_note.overview_card_content')
</gesture-links>
