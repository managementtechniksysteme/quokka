@extends('layouts.app')

@section('content')
    <div class="q-container">
        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Prüfbericht</div>
                    <h1 class="q-title">Anlage {{ $inspectionReport->equipment_identifier }}</h1>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column gap-3">
            <div class="q-card text-center" style="padding: 2.5rem 1.5rem;">
                <svg class="q-empty-icon" style="margin-bottom:.5rem"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-circle"></use></svg>
                <p class="lead text-muted mt-2 mb-0">Vielen Dank, dass Sie den Prüfbericht unterschrieben haben.</p>
            </div>

            <div class="q-card">
                <div class="q-card__head">Prüfbericht herunterladen</div>
                <div class="q-card__body">
                    <p class="text-muted">Klicken Sie auf folgenden Button, um den Prüfbericht im PDF Format zur Archivierung herunterzuladen.</p>

                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('inspection-reports.customer-download', $inspectionReport->downloadRequest->token) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                        Prüfbericht als PDF herunterladen
                    </a>

                    <hr class="my-4" />

                    <p class="text-muted">Alternativ können Sie sich einen Link zum Herunterladen per Email schicken lassen, falls Sie den Prüfbericht auf einem anderen Gerät herunterladen möchten. Geben Sie hierzu bitte die gewünschte Email Adresse in folgendes Feld ein und klicken anschließend auf den Button.</p>

                    <form class="needs-validation" action="{{ route('inspection-reports.customer-email-download-request', $inspectionReport->downloadRequest->token) }}" method="post" novalidate>
                        @csrf

                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-md-6">
                                <input type="email" class="form-control" name="email" placeholder="Email Adresse" value="{{ optional($inspectionReport->project->company->contactPerson)->email ?? $inspectionReport->project->company->email }}" required />
                            </div>
                            <div class="col-12 col-md-auto">
                                <button type="submit" class="btn q-btn d-inline-flex align-items-center gap-2">
                                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                    Link als Email senden
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
