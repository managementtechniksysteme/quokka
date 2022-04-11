@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('service_report.breadcrumb')

            <h3>
                Servicebericht Anfrage zum Herunterladen senden
                <small class="text-muted">{{ $serviceReport->project->name }} #{{ $serviceReport->number }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('service-reports.email-download-request', ['service_report' => $serviceReport, 'redirect' => request()->redirect]) }}" method="post" novalidate>
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <p class="d-inline-flex align-items-center mb-1">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                        </svg>
                        Link zum Herunterladen senden
                    </p>
                    <p class="text-muted">
                        Hier kann die gewünschte Email Adresse angegeben werden, an welche der Link zum Herunterladen per Email gesendeet werden soll.
                    </p>
                    <p class="text-muted">
                        Die Email Addresse der Firma, welcher der Servicebericht zugeordnet ist, wird automatisch eingetragen.
                    </p>
                </div>

                <div class="col-md-8">

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="email@example.com" value="{{ old('email', $serviceReport->project->company->email) }}" />
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
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                        </svg>
                        Anfrage senden
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
