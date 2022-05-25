<div class=" d-none d-md-block">
    @include ('inspection_report.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('inspection-reports.edit', $inspectionReport) }}">
    @include ('inspection_report.overview_card_content')
</gesture-links>
