<div class=" d-none d-md-block">
    @include ('company.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('companies.edit', $company) }}">
    @include ('company.overview_card_content')
</gesture-links>
