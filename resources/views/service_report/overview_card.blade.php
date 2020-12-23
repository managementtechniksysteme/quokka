<div class=" d-none d-md-block">
    @include ('service_report.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('service-reports.edit', $serviceReport) }}">
    @include ('service_report.overview_card_content')
</gesture-links>
