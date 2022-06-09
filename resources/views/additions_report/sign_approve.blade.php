@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon-bs icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                </svg>
                Regiebericht unterschreiben und herunterladen
                <small class="text-muted">{{ $additionsReport->project->name }} #{{ $additionsReport->number }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <div class="text-center mt-4">
            <img class="empty-state" src="{{ asset('svg/approve.svg') }}" alt="done" />
            <p class="lead text-muted mt-1">Vielen Dank, dass Sie den Regiebericht unterschrieben haben.</p>
        </div>

        <h4 class="mt-4">Regiebericht herunterladen</h4>

        <p>Klicken Sie auf folgenden Button, um den Regiebericht im PDF Format zur Archivierung herunterzuladen.</p>

        <div class="text-center">
            <a class="btn btn-primary d-inline-flex align-items-center" href="{{ route('additions-reports.customer-download', $additionsReport->downloadRequest->token) }}">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                </svg>
                Regiebericht als PDF herunterladen
            </a>
        </div>

        <p class="mt-4">Alternativ können Sie sich einen Link zum Herunterladen per Email schicken lassen, falls Sie den Regiebericht auf einem anderen Gerät herunterladen möchten. Geben Sie hierzu bitte die gewünschte Email Adresse in folgendes Feld ein und klicken anschließend auf den Button.</p>

        <form class="needs-validation" action="{{ route('additions-reports.customer-email-download-request', $additionsReport->downloadRequest->token) }}" method="post" novalidate>
            @csrf


            <div class="form-group">
                <div class="col col-md-6 offset-md-3">
                    <input type="email" class="form-control" name="email" placeholder="Email Adresse" value="{{ optional($additionsReport->project->company->contactPerson)->email ?? $additionsReport->project->company->email }}" required />
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-outline-secondary d-inline-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                    </svg>
                    Link als Email senden
                </button>
            </div>

        </form>
    </div>
@endsection
