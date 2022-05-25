@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('inspection_report.breadcrumb')

            <h3>
                Prüfbericht per Email senden
                <small class="text-muted">Anlage {{ $inspectionReport->equipment_identifier }} (Projekt {{ $inspectionReport->project->name }}) vom {{ $inspectionReport->inspected_on }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('inspection-reports.email-download-request', ['inspection_report' => $inspectionReport, 'redirect' => request()->redirect]) }}" method="post" novalidate>
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <p class="d-inline-flex align-items-center mb-1">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                        </svg>
                        Link zum Herunterladen senden
                    </p>
                    <p class="text-muted">
                        Hier kann die gewünschte Email Adresse angegeben werden, an welche der Link zum Herunterladen per Email gesendet werden soll.
                    </p>
                    <p class="text-muted">
                        Die Email Addresse der Firma, welcher der Prüfbericht zugeordnet ist, wird automatisch eingetragen.
                    </p>
                </div>

                <div class="col-md-8">

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="email@example.com" value="{{ old('email', optional($inspectionReport->project->company->contactPerson)->email ?? $inspectionReport->project->company->email) }}" />
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
