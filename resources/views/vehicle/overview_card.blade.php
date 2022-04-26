<div class=" d-none d-md-block">
    @include ('vehicle.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('vehicles.edit', $vehicle) }}">
    @include ('vehicle.overview_card_content')
</gesture-links>
