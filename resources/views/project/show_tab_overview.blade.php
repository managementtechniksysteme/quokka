@extends('project.show')

@section('tab')
    {{-- Übersicht --}}
    <div class="q-card">
        <div class="q-card__head">Übersicht</div>
        <div class="q-card__body">
            <div class="q-inforow">
                <span class="q-avatar q-avatar--round q-avatar--sm q-avatar--{{ $project->company->avatar_colour }}">{{ $project->company->initials }}</span>
                <div class="q-inforow__main">
                    <div class="q-inforow__label">Firma</div>
                    <div class="q-inforow__value text-truncate"><a href="{{ route('companies.show', $project->company) }}">{{ $project->company->full_name }}</a></div>
                </div>
            </div>

            <div class="q-inforow">
                <span class="q-inforow__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg></span>
                <div class="q-inforow__main">
                    <div class="q-inforow__label">Zeitraum</div>
                    <div class="q-inforow__value d-flex align-items-center gap-2">
                        <span @unless($project->starts_on) class="q-inforow__value--empty" @endunless>{{ $project->starts_on ?: 'kein Start' }}</span>
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-right"></use></svg>
                        <span @unless($project->ends_on) class="q-inforow__value--empty" @endunless>{{ $project->ends_on ?: 'kein Ende' }}</span>
                    </div>
                </div>
            </div>

            <div class="q-inforow">
                <span class="q-inforow__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clock"></use></svg></span>
                <div class="q-inforow__main">
                    <div class="q-inforow__label">Vorphase</div>
                    <div class="q-inforow__value">{{ $project->isPreExecutionString }}</div>
                </div>
            </div>

            <div class="q-inforow">
                <span class="q-inforow__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#bar-chart"></use></svg></span>
                <div class="q-inforow__main">
                    <div class="q-inforow__label">In Finanzen</div>
                    <div class="q-inforow__value">{{ $project->includedInFinancesString }}</div>
                </div>
            </div>

            @if($project->current_kilometres)
                <div class="q-inforow">
                    <span class="q-inforow__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#truck"></use></svg></span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Kilometer</div>
                        <div class="q-inforow__value">
                            {{ \App\Helpers\Number::toLocal($project->current_kilometres) }}
                            @can('projects.view.estimates')
                                @if(\App\Models\ApplicationSettings::get()->kilometre_costs)
                                    <span class="text-muted">({{ $currencyUnit }} {{ Number::toLocal($project->current_kilometre_costs) }})</span>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Kosten --}}
    @can('projects.view.estimates')
        @if($project->costs || $project->current_costs || $project->current_billed_financial_costs || $project->wage_costs || $project->current_wage_costs || $project->material_costs || $project->current_material_costs)
            <div class="q-card mt-3">
                <div class="q-card__head">Kosten</div>
                <div class="q-card__body">
                    @if($project->costs || $project->current_costs)
                        <div class="q-inforow">
                            <span class="q-inforow__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg></span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Auftragsvolumen</div>
                                <div class="q-inforow__value d-flex align-items-center flex-wrap gap-1">
                                    @if($project->costs)<span>{{ $currencyUnit }} {{ Number::toLocal($project->costs) }}</span>@endif
                                    @if($project->current_costs)
                                        <span class="text-muted">{{ $project->costs ? '· ' : '' }}gebucht {{ $currencyUnit }} {{ Number::toLocal($project->current_costs) }}</span>
                                        @if($project->current_costs_percentage)<span class="text-muted">({{ Number::toLocal($project->current_costs_percentage, 1) }}%)</span>@include('project.cost_arrow', ['status' => $project->current_costs_status])@endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($project->current_billed_financial_costs || $project->current_costs)
                        <div class="q-inforow">
                            <span class="q-inforow__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg></span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">verrechnet</div>
                                <div class="q-inforow__value d-flex align-items-center flex-wrap gap-1">
                                    @if($project->current_billed_financial_costs)<span>{{ $currencyUnit }} {{ Number::toLocal($project->current_billed_financial_costs) }}</span>@endif
                                    @if($project->current_costs)
                                        <span class="text-muted">{{ $project->current_billed_financial_costs ? '· ' : '' }}gebucht {{ $currencyUnit }} {{ Number::toLocal($project->current_costs) }}</span>
                                        @if($project->current_billed_costs_percentage)<span class="text-muted">({{ Number::toLocal($project->current_billed_costs_percentage, 1) }}%)</span>@include('project.cost_arrow', ['status' => $project->current_billed_costs_status])@endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($project->wage_costs || $project->current_wage_costs)
                        <div class="q-inforow">
                            <span class="q-inforow__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg></span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Lohnvolumen</div>
                                <div class="q-inforow__value d-flex align-items-center flex-wrap gap-1">
                                    @if($project->wage_costs)<span>{{ $currencyUnit }} {{ Number::toLocal($project->wage_costs) }}</span>@endif
                                    @if($project->current_wage_costs)
                                        <span class="text-muted">{{ $project->wage_costs ? '· ' : '' }}gebucht {{ $currencyUnit }} {{ Number::toLocal($project->current_wage_costs) }}</span>
                                        @if($project->current_wage_costs_percentage)<span class="text-muted">({{ Number::toLocal($project->current_wage_costs_percentage, 1) }}%)</span>@include('project.cost_arrow', ['status' => $project->current_wage_costs_status])@endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($project->material_costs || $project->current_material_costs)
                        <div class="q-inforow">
                            <span class="q-inforow__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg></span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Materialvolumen</div>
                                <div class="q-inforow__value d-flex align-items-center flex-wrap gap-1">
                                    @if($project->material_costs)<span>{{ $currencyUnit }} {{ Number::toLocal($project->material_costs) }}</span>@endif
                                    @if($project->current_material_costs)
                                        <span class="text-muted">{{ $project->material_costs ? '· ' : '' }}gebucht {{ $currencyUnit }} {{ Number::toLocal($project->current_material_costs) }}</span>
                                        @if($project->current_material_costs_percentage)<span class="text-muted">({{ Number::toLocal($project->current_material_costs_percentage, 1) }}%)</span>@include('project.cost_arrow', ['status' => $project->current_material_costs_status])@endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endcan

    {{-- Controlling charts --}}
    @can('finances-view')
        <div class="row g-3 mt-0">
            <div class="col-md-6">
                <div class="q-card h-100">
                    <div class="q-card__head">Projektcontrolling</div>
                    <div class="q-card__body">
                        <finance-revenue-expense-chart :revenue="{{ $accountingFinanceData['revenue'] }}" :expense="{{ $accountingFinanceData['expense'] }}" v-cloak></finance-revenue-expense-chart>
                    </div>
                </div>
            </div>
            @if($manualFinanceData)
                <div class="col-md-6">
                    <div class="q-card h-100">
                        <div class="q-card__head">Finanzcontrolling</div>
                        <div class="q-card__body">
                            <finance-volume-chart :total_volume="{{ $manualFinanceData['total_volume'] }}" :billed_volume="{{ $manualFinanceData['billed_volume'] }}" v-cloak></finance-volume-chart>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endcan

    {{-- Bemerkungen --}}
    @if ($project->comment)
        <div class="q-card mt-3">
            <div class="q-card__head">Bemerkungen</div>
            <div class="q-card__body">
                <div class="markdown">
                    {!! Html::fromMarkdown($project->comment) !!}
                </div>
            </div>
        </div>
    @endif
@endsection
