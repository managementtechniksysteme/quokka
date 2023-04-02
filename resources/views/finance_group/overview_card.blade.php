<div class=" d-none d-md-block">
    @include ('finance_group.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('finance-groups.edit', $financeGroup) }}">
    @include ('finance_group.overview_card_content')
</gesture-links>
