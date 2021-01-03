<div class=" d-none d-md-block">
    @include ('employee.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('employees.edit', $employee) }}">
    @include ('employee.overview_card_content')
</gesture-links>
