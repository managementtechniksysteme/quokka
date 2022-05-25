@extends('layouts.app')

@section('content')
    @if($inspectionReport)
        <div class="bg-gray-100 mt-0">
            <div class="container py-4">
                @include('inspection_report.breadcrumb')

                <h3>
                    Prüfbericht unterschreiben
                    <small class="text-muted">Anlage {{ $inspectionReport->equipment_identifier }} (Projekt {{ $inspectionReport->project->name }}) vom {{ $inspectionReport->inspected_on }}</small>
                </h3>
            </div>
        </div>

        <div class="container my-4">
            <p>Unterschreiben Sie bitte in folgendem Feld.<br />
                Am Computer unterschreiben Sie mid der Maus, indem Sie die linke Maustaste gedrückt halten. Am Mobiltelefon, Tablet oder anderen Geräten mit Touchscreen benutzen Sie Ihren Finger oder einen für ihr Gerät passenden Eingabestift.<br />
                Klicken Sie danach auf den <strong>Prüfbericht unterschreiben</strong> Button. Mit dem <strong>Zurücksetzen</strong> Button können Sie die Eingabe löschen.</p>

            <form action="{{ route('inspection-reports.sign', ['inspection_report' => $inspectionReport, 'redirect' => request()->redirect]) }}" method="post">
                @csrf

                <signature-pad></signature-pad>
                <div class="invalid-feedback @error('signature') d-block @enderror">
                    @error('signature')
                    {{ $message }}
                    @enderror
                </div>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <p class="d-inline-flex align-items-center mb-1">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                            </svg>
                            Anfrage zum Download senden
                        </p>
                        <p class="text-muted">
                            Bei Aktivierung der Schaltfläche kann nach dem Unterschreiben direkt eine Anfrage zum Download per Email versendet werden.
                        </p>
                    </div>

                    <div class="col-md-8">
                        <div class="alert alert-info" role="alert">
                            <div class="d-inline-flex align-items-center">
                                <svg class="icon icon-24 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                                </svg>
                                Die Email Adresse kann im nächsten Schritt angegeben werden.
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input @error('send_download_request') is-invalid @enderror" name="send_download_request" id="send_download_request" value="true">
                                <label class="custom-control-label" for="send_download_request">Anfrage zum Download nach dem Unterschreiben senden.</label>
                            </div>
                            <div class="invalid-feedback @error('send_download_request') d-block @enderror">
                                @error('send_download_request')
                                {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            Prüfbericht unterschreiben
                        </button>
                        <a class="btn btn-outline-secondary d-inline-flex align-items-center ml-1" href="">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Zurücksetzen
                        </a>
                    </div>
                </div>

            </form>
        </div>
    @else
        @include('inspection_report.sign_invalid_content')
    @endif
@endsection
