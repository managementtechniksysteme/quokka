@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('delivery-notes.show', $deliveryNote) }}" class="q-appbar__btn" aria-label="Zurück">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $deliveryNote->title }}</span>
@endsection

@section('content')
    <div class="q-container">
        @if($deliveryNote)
            <div class="d-none d-md-block">
                @include('delivery_note.breadcrumb')
            </div>

            <div class="q-page-head d-none d-md-flex">
                <div class="d-flex align-items-center gap-3">
                    <span class="q-avatar">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                    </span>
                    <div>
                        <div class="q-eyebrow">Lieferschein · {{ $deliveryNote->title }}</div>
                        <h1 class="q-title">Unterschreiben lassen</h1>
                    </div>
                </div>
            </div>

            <div class="q-card mt-2 mt-md-4">
                <div class="q-card__head">Unterschrift erfassen</div>
                <div class="q-card__body">
                    <p class="text-muted">
                        Unterschreiben Sie bitte in folgendem Feld.<br />
                        Am Computer unterschreiben Sie mit der Maus, indem Sie die linke Maustaste gedrückt halten. Am Mobiltelefon, Tablet oder anderen Geräten mit Touchscreen benutzen Sie Ihren Finger oder einen für Ihr Gerät passenden Eingabestift.<br />
                        Klicken Sie danach auf den <strong>Lieferschein unterschreiben</strong> Button. Mit dem <strong>Zurücksetzen</strong> Button können Sie die Eingabe löschen.
                    </p>

                    <form action="{{ route('delivery-notes.sign', ['delivery_note' => $deliveryNote, 'redirect' => request()->redirect]) }}" method="post">
                        @csrf

                        <signature-pad></signature-pad>
                        <div class="invalid-feedback @error('signature') d-block @enderror">
                            @error('signature')
                                {{ $message }}
                            @enderror
                        </div>

                        <div class="q-banner q-banner--info mt-4">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
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
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                                Lieferschein unterschreiben
                            </button>
                            <a class="btn q-btn d-inline-flex align-items-center gap-2" href="">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                                Zurücksetzen
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        @else
            @include('delivery_note.sign_invalid_content')
        @endif
    </div>
@endsection
