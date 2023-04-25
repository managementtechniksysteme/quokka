@extends('layouts.app')

@section('content')
    @if($deliveryNote)
        <div class="bg-gray-100 mt-0">
            <div class="container py-4">
                <h3>
                    <svg class="icon icon-baseline text-muted mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                    </svg>
                    Lieferschein unterschreiben und herunterladen
                    <small class="text-muted">{{ $deliveryNote->title }}</small>
                </h3>
            </div>
        </div>

        <div class="container my-4">
            <p class="lead d-flex align-items-center">
                <svg class="text-muted icon icon-20 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                </svg>
                <span class="text-muted mr-3">Projekt: </span>
                {{ $deliveryNote->project->name }}
            </p>

            @if($deliveryNote->comment)
                <p class="lead d-flex align-items-center">
                    <svg class="text-muted icon icon-20 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                    </svg>
                    <span class="text-muted mr-3">Bemerkungen</span>
                </p>

                <div class="markdown">
                    {!! Html::fromMarkdown($deliveryNote->comment) !!}
                </div>
            @endif

            <div class="alert alert-info mt-4" role="alert">
                <div class="d-inline-flex align-items-center">
                    <svg class="icon icon-24 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                    </svg>
                    Der Lieferschein kann nach erfolgreicher Unterschrift heruntergeladen werden.
                </div>
            </div>

            <h4 class="mt-4">Lieferschein unterschreiben</h4>

            <p>Unterschreiben Sie bitte in folgendem Feld.<br />
            Am Computer unterschreiben Sie mid der Maus, indem Sie die linke Maustaste gedrückt halten. Am Mobiltelefon, Tablet oder anderen Geräten mit Touchscreen benutzen Sie Ihren Finger oder einen für ihr Gerät passenden Eingabestift.<br />
            Klicken Sie danach auf den <strong>Lieferschein unterschreiben</strong> Button. Mit dem <strong>Zurücksetzen</strong> Button können Sie die Eingabe löschen.</p>

            <form action="{{ route('delivery-notes.customer-sign', $deliveryNote->signatureRequest->token) }}" method="post">
                @csrf

                <signature-pad></signature-pad>
                <div class="invalid-feedback @error('signature') d-block @enderror">
                    @error('signature')
                        {{ $message }}
                    @enderror
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            Lieferschein unterschreiben
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
        @include('delivery_note.sign_invalid_content')
    @endif
@endsection