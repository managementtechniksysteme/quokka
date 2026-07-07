@extends('layouts.app')

@section('content')
    <div class="q-container">
        @if($constructionReport)
            @include('construction_report.breadcrumb')

            <div class="q-page-head">
                <div class="d-flex align-items-center gap-3">
                    <span class="q-avatar">
                        <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                    </span>
                    <div>
                        <div class="q-eyebrow">Bautagesbericht · #{{ $constructionReport->number }}</div>
                        <h1 class="q-title">Unterschreiben lassen</h1>
                    </div>
                </div>
            </div>

            <div class="q-card">
                <div class="q-card__head">Unterschrift erfassen</div>
                <div class="q-card__body">
                    <p class="text-muted">
                        Unterschreiben Sie bitte in folgendem Feld.<br />
                        Am Computer unterschreiben Sie mit der Maus, indem Sie die linke Maustaste gedrückt halten. Am Mobiltelefon, Tablet oder anderen Geräten mit Touchscreen benutzen Sie Ihren Finger oder einen für Ihr Gerät passenden Eingabestift.<br />
                        Klicken Sie danach auf den <strong>Bautagesbericht unterschreiben</strong> Button. Mit dem <strong>Zurücksetzen</strong> Button können Sie die Eingabe löschen.
                    </p>

                    <form action="{{ route('construction-reports.sign', ['construction_report' => $constructionReport, 'redirect' => request()->redirect]) }}" method="post">
                        @csrf

                        <signature-pad></signature-pad>
                        <div class="invalid-feedback @error('signature') d-block @enderror">
                            @error('signature')
                                {{ $message }}
                            @enderror
                        </div>

                        <div class="q-banner q-banner--info mt-4">
                            <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use></svg>
                            <span>Bei aktivierter Schaltfläche kann nach dem Unterschreiben direkt eine Anfrage zum Download per Email versendet werden. Die Email Adresse kann im nächsten Schritt angegeben werden.</span>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input @error('send_download_request') is-invalid @enderror" name="send_download_request" id="send_download_request" value="true">
                            <label class="form-check-label" for="send_download_request">Anfrage zum Download nach dem Unterschreiben senden.</label>
                            <div class="invalid-feedback @error('send_download_request') d-block @enderror">
                                @error('send_download_request')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                                Bautagesbericht unterschreiben
                            </button>
                            <a class="btn q-btn d-inline-flex align-items-center gap-2" href="">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use></svg>
                                Zurücksetzen
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        @else
            @include('construction_report.sign_invalid_content')
        @endif
    </div>
@endsection
