<div class=" d-none d-md-block">
    @include ('role.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('roles.edit', $role) }}">
    @include ('role.overview_card_content')
</gesture-links>
