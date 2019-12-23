<div class=" d-none d-md-block">
    @include ('comment.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('comments.edit', $comment) }}">
    @include ('comment.overview_card_content')
</gesture-links>
