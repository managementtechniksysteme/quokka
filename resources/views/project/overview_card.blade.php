<div class=" d-none d-md-block">
    @include ('project.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('projects.edit', $project) }}">
    @include ('project.overview_card_content')
</gesture-links>
