@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('flow-meter-inspection-reports.index') }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
    </a>
    <span class="q-appbar__title">Prüfbericht anlegen</span>
@endsection

@section('content')
    <div class="q-container">
        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Prüfbericht für Durchflussmesseinrichtungen anlegen</div>
                    <h1 class="q-title">Neuer Prüfbericht für Durchflussmesseinrichtungen</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" enctype="multipart/form-data" action="{{ route('flow-meter-inspection-reports.store') }}" method="post" novalidate>
            @include('flow_meter_inspection_report.fields', ['flowMeterInspectionReport' => $flowMeterInspectionReport, 'comparison_measurement_q_percentages' => $comparison_measurement_q_percentages, 'currentProject' => $currentProject, 'projects' => $projects, 'currentAttachments' => $currentAttachments])

            <div class="q-form-actions q-form-actions--solo-mobile">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ route('flow-meter-inspection-reports.index') }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Prüfbericht speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
