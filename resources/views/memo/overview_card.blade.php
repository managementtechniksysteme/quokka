<div class=" d-none d-md-block">
    @include ('memo.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('memos.edit', $memo) }}">
    @include ('memo.overview_card_content')
</gesture-links>
