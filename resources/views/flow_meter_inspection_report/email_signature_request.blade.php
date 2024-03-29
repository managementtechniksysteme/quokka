@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('flow_meter_inspection_report.breadcrumb')

            <h3>
                Prüfbericht für Durchflussmesseinrichtungen per Email senden
                <small class="text-muted">Anlage {{ $flowMeterInspectionReport->equipment_identifier }} (Projekt {{ $flowMeterInspectionReport->project->name }}) vom {{ $flowMeterInspectionReport->inspected_on }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('flow-meter-inspection-reports.email-signature-request', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => request()->redirect]) }}" method="post" novalidate>
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <p class="d-inline-flex align-items-center mb-1">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                        </svg>
                        Anfrage zur Unterschrift senden
                    </p>
                    <p class="text-muted">
                        Hier kann die gewünschte Email Adresse angegeben werden, an welche eine Anfrage zur Unterschrift per Email gesendet werden soll.
                    </p>
                    <p class="text-muted">
                        Die Email Addresse der Firma, welcher der Prüfbericht zugeordnet ist, wird automatisch eingetragen.
                    </p>
                </div>

                <div class="col-md-8">

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="email@example.com" value="{{ old('email', optional($flowMeterInspectionReport->project->company->contactPerson)->email ?? $flowMeterInspectionReport->project->company->email) }}" />
                        <div class="invalid-feedback">
                            @error('email')
                            {{ $message }}
                            @else
                                Gib bitte eine gültige E-Mail Addresse ein.
                                @enderror
                        </div>
                    </div>

                </div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                        </svg>
                        Anfrage senden
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
