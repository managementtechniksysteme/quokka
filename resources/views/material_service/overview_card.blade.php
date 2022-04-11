<div class=" d-none d-md-block">
    @include ('material_service.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('material-services.edit', $materialService) }}">
    @include ('material_service.overview_card_content')
</gesture-links>
