@extends('layouts.app')

@section('content')
    <div class="q-container">
        @if($deliveryNote)
            <div class="q-page-head">
                <div class="d-flex align-items-center gap-3">
                    <span class="q-avatar">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#box-seam"></use></svg>
                    </span>
                    <div>
                        <div class="q-eyebrow">Lieferschein</div>
                        <h1 class="q-title">{{ $deliveryNote->title }}</h1>
                    </div>
                </div>
            </div>

            <div class="q-statbar mb-4">
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Projekt</span>
                    <span class="q-statbar__value text-truncate">{{ $deliveryNote->project->name }}</span>
                </div>
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Datum</span>
                    <span class="q-statbar__value">{{ $deliveryNote->written_on }}</span>
                </div>
            </div>

            <div class="d-flex flex-column gap-3">
                @if($deliveryNote->comment)
                    <div class="q-card">
                        <div class="q-card__head">Bemerkungen</div>
                        <div class="q-card__body">
                            <div class="markdown">
                                {!! Html::fromMarkdown($deliveryNote->comment) !!}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Prüfen --}}
                <div class="q-card">
                    <div class="q-card__head">Lieferschein prüfen</div>
                    <div class="q-card__body">
                        <p class="text-muted">
                            Zur Überprüfung kann der Lieferschein ohne Unterschrift angezeigt werden. Klicken Sie auf den Button,
                            um den Lieferschein zu überprüfen.
                        </p>
                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('delivery-notes.customer-download', $deliveryNote->downloadRequest->token) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#eye"></use></svg>
                            Lieferschein anzeigen
                        </a>
                    </div>
                </div>

                {{-- Unterschreiben --}}
                <div class="q-card">
                    <div class="q-card__head">Lieferschein unterschreiben</div>
                    <div class="q-card__body">
                        <div class="q-banner q-banner--info">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
                            <span>Der Lieferschein kann nach erfolgreicher Unterschrift erneut heruntergeladen werden.</span>
                        </div>

                        <p class="text-muted">
                            Unterschreiben Sie bitte in folgendem Feld.<br />
                            Am Computer unterschreiben Sie mit der Maus, indem Sie die linke Maustaste gedrückt halten. Am Mobiltelefon, Tablet oder anderen Geräten mit Touchscreen benutzen Sie Ihren Finger oder einen für Ihr Gerät passenden Eingabestift.<br />
                            Klicken Sie danach auf den <strong>Lieferschein unterschreiben</strong> Button. Mit dem <strong>Zurücksetzen</strong> Button können Sie die Eingabe löschen.
                        </p>

                        <form action="{{ route('delivery-notes.customer-sign', $deliveryNote->signatureRequest->token) }}" method="post">
                            @csrf

                            <signature-pad></signature-pad>
                            <div class="invalid-feedback @error('signature') d-block @enderror">
                                @error('signature')
                                    {{ $message }}
                                @enderror
                            </div>

                            <div class="mt-3 d-flex flex-wrap gap-2">
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
            </div>
        @else
            @include('delivery_note.sign_invalid_content')
        @endif
    </div>
@endsection
