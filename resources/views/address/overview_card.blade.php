<div class=" d-none d-md-block">
    @include ('address.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('addresses.edit', $address) }}">
    @include ('address.overview_card_content')
</gesture-links>
