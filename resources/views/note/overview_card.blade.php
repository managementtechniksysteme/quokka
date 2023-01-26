<div class=" d-none d-md-block">
    @include ('note.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('notes.edit', $note) }}">
    @include ('note.overview_card_content')
</gesture-links>
