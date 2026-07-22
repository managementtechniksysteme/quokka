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
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Finanzen</div>
                    <h1 class="q-title">Projekt Finanzübersicht</h1>
                </div>
            </div>
            @can('finances-createpdf')
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('project-finances.download') }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
        </div>

        {{-- Mobile: same treatment as finances/index — app bar already
             carries the page title, PDF action collapses to icon-only
             (2026-07-22, user report). --}}
        @can('finances-createpdf')
            <div class="d-flex d-md-none justify-content-end mb-3">
                <a class="btn q-btn q-btn-icon" href="{{ route('project-finances.download') }}" aria-label="PDF erstellen">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                </a>
            </div>
        @endcan

        {{-- Overview: Open + Pre-execution projects --}}
        <div class="row g-4 mt-md-2">

            <div class="col-lg-6">
                <div class="q-card h-100">
                    <div class="q-card__head">Aktuell offene Projekte</div>
                    <div style="border-bottom: 1px solid var(--q-border-2)">
                        @include('partials.finance_stat_row', ['currencyUnit' => $currencyUnit, 'stats' => [
                            ['label' => 'Auftragsvolumen', 'value' => $currentlyOpenProjectsData['total_volume'], 'variant' => 'success'],
                            ['label' => 'verrechnet', 'value' => $currentlyOpenProjectsData['billed_volume'], 'variant' => 'danger'],
                            ['label' => 'offen', 'value' => $currentlyOpenProjectsData['total_volume'] + $currentlyOpenProjectsData['billed_volume'], 'variant' => $currentlyOpenProjectsData['total_volume'] + $currentlyOpenProjectsData['billed_volume'] >= 0 ? 'success' : 'danger'],
                        ]])
                    </div>
                    <finance-volume-chart :total_volume="{{ $currentlyOpenProjectsData['total_volume'] }}" :billed_volume="{{ $currentlyOpenProjectsData['billed_volume'] }}" v-cloak></finance-volume-chart>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="q-card h-100">
                    <div class="q-card__head">Projekte in der Vorphase</div>
                    <div style="border-bottom: 1px solid var(--q-border-2)">
                        @include('partials.finance_stat_row', ['currencyUnit' => $currencyUnit, 'stats' => [
                            ['label' => 'Auftragsvolumen', 'value' => $preExecutionProjectsData['total_volume'], 'variant' => 'success'],
                            ['label' => 'verrechnet', 'value' => $preExecutionProjectsData['billed_volume'], 'variant' => 'danger'],
                            ['label' => 'offen', 'value' => $preExecutionProjectsData['total_volume'] + $preExecutionProjectsData['billed_volume'], 'variant' => $preExecutionProjectsData['total_volume'] + $preExecutionProjectsData['billed_volume'] >= 0 ? 'success' : 'danger'],
                        ]])
                    </div>
                    <finance-volume-chart :total_volume="{{ $preExecutionProjectsData['total_volume'] }}" :billed_volume="{{ $preExecutionProjectsData['billed_volume'] }}" v-cloak></finance-volume-chart>
                </div>
            </div>

        </div>

        {{-- Per-project filter --}}
        <div class="d-flex align-items-center gap-2 mt-4 mb-3">
            <h2 class="q-subhead">Übersicht einzelner Projekte</h2>
        </div>

        <div class="q-form-section mb-0">
            <div class="q-form-section__body">
                <form class="needs-validation d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3" action="{{ route('project-finances.index') }}" method="get" novalidate>
                    <div class="flex-grow-1">
                        <project-dropdown :projects="{{ $projects }}" :current_project="{{ $currentProject ?? 'null' }}" inputname="project" v-cloak></project-dropdown>
                        @error('project_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center justify-content-center gap-2 flex-shrink-0">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#eye"></use></svg>
                        Anzeigen
                    </button>
                </form>
            </div>
        </div>

        @if($projectData)
            <div class="q-card mt-3">
                <div style="border-bottom: 1px solid var(--q-border-2)">
                    @include('partials.finance_stat_row', ['currencyUnit' => $currencyUnit, 'stats' => [
                        ['label' => 'Auftragsvolumen', 'value' => $projectData['total_volume'], 'variant' => 'success'],
                        ['label' => 'verrechnet', 'value' => $projectData['billed_volume'], 'variant' => 'danger'],
                        ['label' => 'offen', 'value' => $projectData['total_volume'] + $projectData['billed_volume'], 'variant' => $projectData['total_volume'] + $projectData['billed_volume'] >= 0 ? 'success' : 'danger'],
                    ]])
                </div>
                <finance-volume-chart :total_volume="{{ $projectData['total_volume'] }}" :billed_volume="{{ $projectData['billed_volume'] }}" v-cloak></finance-volume-chart>
            </div>
        @endif

    </div>
@endsection
