<div class=" d-none d-md-block">
    @include ('construction_report.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('construction-reports.edit', $constructionReport) }}">
    @include ('construction_report.overview_card_content')
</gesture-links>
