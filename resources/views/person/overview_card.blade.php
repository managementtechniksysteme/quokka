<div class=" d-none d-md-block">
    @include ('person.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('people.edit', $person) }}">
    @include ('person.overview_card_content')
</gesture-links>
