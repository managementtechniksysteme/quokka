@extends('layouts.app')

@php use \App\Models\Project; @endphp

@if (old('project'))
    @php $currentProject = Project::find(old('project')); @endphp
@endif

@section('content')
    <div class="q-container">

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#bar-chart"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Finanzen</div>
                    <h1 class="q-title">Projektcontrolling</h1>
                </div>
            </div>
        </div>

        <div class="q-banner q-banner--info mt-4">
            <svg class="icon-bs icon-16 flex-shrink-0"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
            <span>
                Die Eingaben für Start- und Enddatum haben nur Auswirkungen auf das <strong>Projektcontrolling</strong>.
                Wenn keine Filter angegeben sind, werden Start bzw. Ende des Projektes verwendet.
            </span>
        </div>

        <div class="q-form-section mb-0 mt-3">
            <div class="q-form-section__body">
                <form class="needs-validation d-flex flex-wrap align-items-end gap-3" action="{{ route('project-controlling.index') }}" method="get" novalidate>
                    <div class="flex-grow-1" style="min-width: 200px">
                        <label class="form-label small fw-semibold text-muted">Projekt</label>
                        <project-dropdown :projects="{{ $projects }}" :current_project="{{ $currentProject ?? 'null' }}" inputname="project" v-cloak></project-dropdown>
                        @error('project_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="start" class="form-label small fw-semibold text-muted">Startdatum</label>
                        <input type="date" class="form-control @error('start') is-invalid @enderror" id="start" name="start" value="{{ old('start', $start?->format('Y-m-d')) ?? '' }}" />
                        @error('start')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="end" class="form-label small fw-semibold text-muted">Enddatum</label>
                        <input type="date" class="form-control @error('end') is-invalid @enderror" id="end" name="end" value="{{ old('end', $end?->format('Y-m-d')) ?? '' }}" />
                        @error('end')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center justify-content-center gap-2 q-btn-full-mobile">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#eye"></use></svg>
                        Anzeigen
                    </button>
                </form>
            </div>
        </div>

        @if($currentProject)
            <div class="row g-4 mt-1">

                <div class="col-lg-6">
                    <div class="q-card h-100">
                        <div class="q-card__head">Projektcontrolling</div>
                        <div style="border-bottom: 1px solid var(--q-border-2)">
                            @include('partials.finance_stat_row', ['currencyUnit' => $currencyUnit, 'stats' => [
                                ['label' => 'Einnahmen', 'value' => $accountingFinanceData['revenue'], 'variant' => 'success'],
                                ['label' => 'Ausgaben', 'value' => $accountingFinanceData['expense'], 'variant' => 'danger'],
                                ['label' => 'Differenz', 'value' => $accountingFinanceData['revenue'] + $accountingFinanceData['expense'], 'variant' => $accountingFinanceData['revenue'] + $accountingFinanceData['expense'] >= 0 ? 'success' : 'danger'],
                            ]])
                        </div>
                        <finance-revenue-expense-chart :revenue="{{ $accountingFinanceData['revenue'] }}" :expense="{{ $accountingFinanceData['expense'] }}" v-cloak></finance-revenue-expense-chart>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="q-card h-100">
                        <div class="q-card__head">Finanzcontrolling</div>
                        <div style="border-bottom: 1px solid var(--q-border-2)">
                            @include('partials.finance_stat_row', ['currencyUnit' => $currencyUnit, 'stats' => [
                                ['label' => 'Auftragsvolumen', 'value' => $manuelFinanceData['total_volume'], 'variant' => 'success'],
                                ['label' => 'verrechnet', 'value' => $manuelFinanceData['billed_volume'], 'variant' => 'danger'],
                                ['label' => 'offen', 'value' => $manuelFinanceData['total_volume'] + $manuelFinanceData['billed_volume'], 'variant' => $manuelFinanceData['total_volume'] + $manuelFinanceData['billed_volume'] >= 0 ? 'success' : 'danger'],
                            ]])
                        </div>
                        <finance-volume-chart :total_volume="{{ $manuelFinanceData['total_volume'] }}" :billed_volume="{{ $manuelFinanceData['billed_volume'] }}" v-cloak></finance-volume-chart>
                    </div>
                </div>

            </div>
        @endif

    </div>
@endsection
