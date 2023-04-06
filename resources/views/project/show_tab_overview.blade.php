@extends('project.show')

@section('tab')

    <div class="row">
        <div class="col-sm-2">
            <div class="text-muted d-flex align-items-center">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                </svg>
                Firma
            </div>
        </div>
        <div class="col">
            <a href="{{ route('companies.show', $project->company) }}">{{ $project->company->full_name }}</a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-2">
            <div class="text-muted d-flex align-items-center">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                </svg>
                Zeitraum
            </div>
        </div>
        <div class="col">
            <div class="d-flex align-items-center">
                {{ $project->starts_on ? $project->starts_on : 'kein Start angegeben' }}
                <svg class="icon icon-16 mx-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                </svg>
                {{ $project->ends_on ?? 'kein Ende angegeben' }}
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-2">
            <div class="text-muted d-flex align-items-center">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                </svg>
                Vorphase
            </div>
        </div>
        <div class="col">
            {{ $project->isPreExecutionString }}
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-2">
            <div class="text-muted d-flex align-items-center">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#bar-chart-2"></use>
                </svg>
                In Finanzen
            </div>
        </div>
        <div class="col">
            {{ $project->includedInFinancesString }}
        </div>
    </div>
    @can('projects.view.estimates')
        @if($project->costs || $project->current_costs)
            <div class="row mt-3">
                <div class="col-sm-2">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                        </svg>
                        Auftragsvolumen
                    </div>
                </div>
                <div class="col">
                    {{ $project->costs ? $currencyUnit . ' ' . Number::toLocal($project->costs) :  '' }}
                    @if($project->current_costs)
                        {{ $project->costs ? '-' : '' }}
                        gebucht: {{ $currencyUnit . ' ' . Number::toLocal($project->current_costs) }}
                        @if($project->current_costs_percentage)
                            ({{ Number::toLocal($project->current_costs_percentage, 1) }}%)
                            <svg class="icon icon-16 ml-2 text-{{ $project->current_costs_status }}">
                                @switch($project->current_costs_status)
                                    @case('success')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                        @break
                                    @case('warning')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down-right"></use>
                                        @break
                                    @case('danger')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                        @break
                                @endswitch
                            </svg>
                        @endif
                    @endif
                </div>
            </div>
        @endif
        @if($project->current_billed_financial_costs || $project->current_costs)
            <div class="row mt-3">
                <div class="col-sm-2">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                        </svg>
                        verrechnet
                    </div>
                </div>
                <div class="col">
                    {{ $project->current_billed_financial_costs ? $currencyUnit . ' ' . Number::toLocal($project->current_billed_financial_costs) :  '' }}
                    @if($project->current_costs)
                        {{ $project->current_billed_financial_costs ? '-' : '' }}
                        gebucht: {{ $currencyUnit . ' ' . Number::toLocal($project->current_costs) }}
                        @if($project->current_billed_costs_percentage)
                            ({{ Number::toLocal($project->current_billed_costs_percentage, 1) }}%)
                            <svg class="icon icon-16 ml-2 text-{{ $project->current_billed_costs_status }}">
                                @switch($project->current_billed_costs_status)
                                    @case('success')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                        @break
                                    @case('warning')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down-right"></use>
                                        @break
                                    @case('danger')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                        @break
                                @endswitch
                            </svg>
                        @endif
                    @endif
                </div>
            </div>
        @endif
        @if($project->wage_costs || $project->current_wage_costs)
            <div class="row mt-3">
                <div class="col-sm-2">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                        </svg>
                        Lohnvolumen
                    </div>
                </div>
                <div class="col">
                    {{ $project->wage_costs ? $currencyUnit . ' ' . Number::toLocal($project->wage_costs) : '' }}
                    @if($project->current_wage_costs)
                        {{ $project->wage_costs ? '-' : '' }}
                        gebucht: {{ $currencyUnit . ' ' . Number::toLocal($project->current_wage_costs) }}
                        @if($project->current_wage_costs_percentage)
                            ({{ Number::toLocal($project->current_wage_costs_percentage, 1) }}%)
                            <svg class="icon icon-16 ml-2 text-{{ $project->current_wage_costs_status }}">
                                @switch($project->current_wage_costs_status)
                                    @case('success')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                        @break
                                    @case('warning')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down-right"></use>
                                        @break
                                    @case('danger')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                        @break
                                @endswitch
                            </svg>
                        @endif
                    @endif
                </div>
            </div>
        @endif
        @if($project->material_costs || $project->current_material_costs)
            <div class="row mt-3">
                <div class="col-sm-2">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                        </svg>
                        Materialvolumen
                    </div>
                </div>
                <div class="col">
                    {{ $project->material_costs ? $currencyUnit . ' ' . Number::toLocal($project->material_costs) :  '' }}
                    @if($project->current_material_costs)
                        {{ $project->material_costs ? '-' : '' }}
                        gebucht: {{ $currencyUnit . ' ' . Number::toLocal($project->current_material_costs) }}
                        @if($project->current_material_costs_percentage)
                            ({{ Number::toLocal($project->current_material_costs_percentage, 1) }}%)
                            <svg class="icon icon-16 ml-2 text-{{ $project->current_material_costs_status }}">
                                @switch($project->current_material_costs_status)
                                    @case('success')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                        @break
                                    @case('warning')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down-right"></use>
                                        @break
                                    @case('danger')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                        @break
                                @endswitch
                            </svg>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    @endcan
    @if($project->current_kilometres)
        <div class="row mt-3">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#truck"></use>
                    </svg>
                    Kilometer
                </div>
            </div>
            <div class="col">
                {{ \App\Helpers\Number::toLocal($project->current_kilometres) }}
                @can('projects.view.estimates')
                    @if(\App\Models\ApplicationSettings::get()->kilometre_costs)
                        ({{ $currencyUnit . ' ' . Number::toLocal($project->current_kilometre_costs) }})
                    @endif
                @endcan
            </div>
        </div>
    @endif

    @can('finances-view')
        <div class="row mt-4">
            <div class="col-md-6 mb-2">
                <h4>Projektcontrolling</h4>
                <finance-revenue-expense-chart :revenue="{{ $accountingFinanceData['revenue'] }}" :expense="{{ $accountingFinanceData['expense'] }}" v-cloak></finance-revenue-expense-chart>
            </div>
            @if($manualFinanceData)
                <div class="col-md-6 mb-2">
                    <h4>Finanzcontrolling</h4>
                    <finance-volume-chart :total_volume="{{ $manualFinanceData['total_volume'] }}" :billed_volume="{{ $manualFinanceData['billed_volume'] }}" v-cloak></finance-volume-chart>
                </div>
            @endif
        </div>
    @endcan

    @if ($project->comment)
        <div class="text-muted d-flex align-items-center mt-4">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Bemerkungen
        </div>
        <div class="markdown">
            {!! Html::fromMarkdown($project->comment) !!}
        </div>
    @endif
@endsection
