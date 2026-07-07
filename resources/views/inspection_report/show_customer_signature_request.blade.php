@extends('layouts.app')

@section('content')
    <div class="q-container">
        @if($inspectionReport)
            <div class="q-page-head">
                <div class="d-flex align-items-center gap-3">
                    <span class="q-avatar">
                        <svg class="icon-bs icon-20"><use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
                    </span>
                    <div>
                        <div class="q-eyebrow">Prüfbericht</div>
                        <h1 class="q-title">Anlage {{ $inspectionReport->equipment_identifier }}</h1>
                    </div>
                </div>
            </div>

            <div class="q-statbar mb-4">
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Techniker</span>
                    <span class="q-statbar__value">{{ $inspectionReport->employee->person->name }}</span>
                </div>
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Datum</span>
                    <span class="q-statbar__value">{{ $inspectionReport->inspected_on }}</span>
                </div>
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Wetter</span>
                    <span class="q-statbar__value d-inline-flex align-items-center gap-2">
                        <svg class="icon icon-16">
                            @switch($inspectionReport->weather)
                                @case('sunny')<use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>@break
                                @case('cloudy')<use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cloud"></use>@break
                                @case('rainy')<use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cloud-rain"></use>@break
                                @case('snowy')<use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cloud-snow"></use>@break
                            @endswitch
                        </svg>
                        {{ __($inspectionReport->weather) }}
                    </span>
                </div>
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Anlagentyp</span>
                    <span class="q-statbar__value">{{ $inspectionReport->equipment_type }}</span>
                </div>
            </div>

            <div class="d-flex flex-column gap-3">
                {{-- Messwerte --}}
                @include('inspection_report._measurements')

                @if ($inspectionReport->comment)
                    <div class="q-card">
                        <div class="q-card__head">Durchgeführte Arbeiten und Bemerkungen</div>
                        <div class="q-card__body">
                            <div class="markdown">
                                {!! Html::fromMarkdown($inspectionReport->comment) !!}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Unterschreiben --}}
                <div class="q-card">
                    <div class="q-card__head">Prüfbericht unterschreiben</div>
                    <div class="q-card__body">
                        <div class="q-banner q-banner--info">
                            <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use></svg>
                            <span>Der Prüfbericht kann nach erfolgreicher Unterschrift heruntergeladen werden.</span>
                        </div>

                        <p class="text-muted">
                            Unterschreiben Sie bitte in folgendem Feld.<br />
                            Am Computer unterschreiben Sie mit der Maus, indem Sie die linke Maustaste gedrückt halten. Am Mobiltelefon, Tablet oder anderen Geräten mit Touchscreen benutzen Sie Ihren Finger oder einen für Ihr Gerät passenden Eingabestift.<br />
                            Klicken Sie danach auf den <strong>Prüfbericht unterschreiben</strong> Button. Mit dem <strong>Zurücksetzen</strong> Button können Sie die Eingabe löschen.
                        </p>

                        <form action="{{ route('inspection-reports.customer-sign', $inspectionReport->signatureRequest->token) }}" method="post">
                            @csrf

                            <signature-pad></signature-pad>
                            <div class="invalid-feedback @error('signature') d-block @enderror">
                                @error('signature')
                                    {{ $message }}
                                @enderror
                            </div>

                            <div class="mt-3 d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                                    <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                                    Prüfbericht unterschreiben
                                </button>
                                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="">
                                    <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use></svg>
                                    Zurücksetzen
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            @include('inspection_report.sign_invalid_content')
        @endif
    </div>
@endsection
