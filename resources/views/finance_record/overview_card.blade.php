<div class=" d-none d-md-block">
    @include ('finance_record.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('finance-records.edit', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}">
    @include ('finance_record.overview_card_content')
</gesture-links>
