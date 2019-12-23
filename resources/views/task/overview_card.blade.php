<div class=" d-none d-md-block">
    @include ('task.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('tasks.edit', $task) }}">
    @include ('task.overview_card_content')
</gesture-links>
