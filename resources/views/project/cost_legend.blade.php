{{-- Shared cost-estimate arrow legend for project lists (index + company detail
     Projekte tab). Needs $projects (paginator) + the $project*WarningPercentage
     vars, which both controllers provide. Keep a single source so the two lists
     never drift apart. --}}
@if(Auth::user()->can('projects.view.estimates') && Auth::user()->settings->show_cost_estimates)
    @if($projects->count() > 0 && ($projectOverallCostsWarningPercentage || $projectBilledCostsWarningPercentage || $projectMaterialCostsWarningPercentage || $projectWageCostsWarningPercentage))
        <p class="q-legend mt-3">
            Die Pfeile für die
            <b><u>G</u></b>esamt, <b><u>V</u></b>errechneten, <b><u>L</u></b>ohn und <b><u>M</u></b>aterialkosten zeigen:
            <svg class="icon icon-baseline text-success"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use></svg> unter der Warnschwelle ·
            <svg class="icon icon-baseline text-warning"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down-right"></use></svg> zwischen Warnschwelle und Schätzung ·
            <svg class="icon icon-baseline text-danger"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use></svg> über der Schätzung.
            Warnschwellen:
            @if($projectOverallCostsWarningPercentage) Gesamt {{ $projectOverallCostsWarningPercentage }}% @endif
            @if($projectBilledCostsWarningPercentage) Verrechnet {{ $projectBilledCostsWarningPercentage }}% @endif
            @if($projectWageCostsWarningPercentage) Lohn {{ $projectWageCostsWarningPercentage }}% @endif
            @if($projectMaterialCostsWarningPercentage) Material {{ $projectMaterialCostsWarningPercentage }}% @endif
        </p>
    @endif
@endif
