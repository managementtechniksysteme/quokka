@extends('layouts.app')

@section('content')
    <div class="q-container">
        @if($serviceReport)
            <div class="q-page-head">
                <div class="d-flex align-items-center gap-3">
                    <span class="q-avatar">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use></svg>
                    </span>
                    <div>
                        <div class="q-eyebrow">Servicebericht · #{{ $serviceReport->number }}</div>
                        <h1 class="q-title">{{ $serviceReport->project->name }}</h1>
                    </div>
                </div>
            </div>

            <div class="q-statbar mb-4">
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Techniker</span>
                    <span class="q-statbar__value">{{ $serviceReport->employee->person->name }}</span>
                </div>
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Stunden</span>
                    <span class="q-statbar__value">{{ Number::toLocal($serviceReport->total_hours) }} h</span>
                </div>
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Kilometer</span>
                    <span class="q-statbar__value">{{ Number::toLocal($serviceReport->total_kilometres) }} km</span>
                </div>
            </div>

            <div class="d-flex flex-column gap-3">
                <div class="q-card">
                    <div class="q-card__head">Serviceleistungen</div>
                    @include('service_report.show_services')
                </div>

                @if($serviceReport->comment)
                    <div class="q-card">
                        <div class="q-card__head">Kurzbericht</div>
                        <div class="q-card__body">
                            <div class="markdown">
                                {!! Html::fromMarkdown($serviceReport->comment) !!}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="q-card">
                    <div class="q-card__head">Servicebericht unterschreiben</div>
                    <div class="q-card__body">
                        <div class="q-banner q-banner--info">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
                            <span>Der Servicebericht kann nach erfolgreicher Unterschrift heruntergeladen werden.</span>
                        </div>

                        <p class="text-muted">
                            Unterschreiben Sie bitte in folgendem Feld.<br />
                            Am Computer unterschreiben Sie mit der Maus, indem Sie die linke Maustaste gedrückt halten. Am Mobiltelefon, Tablet oder anderen Geräten mit Touchscreen benutzen Sie Ihren Finger oder einen für Ihr Gerät passenden Eingabestift.<br />
                            Klicken Sie danach auf den <strong>Servicebericht unterschreiben</strong> Button. Mit dem <strong>Zurücksetzen</strong> Button können Sie die Eingabe löschen.
                        </p>

                        <form action="{{ route('service-reports.customer-sign', $serviceReport->signatureRequest->token) }}" method="post">
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
                                    Servicebericht unterschreiben
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
            @include('service_report.sign_invalid_content')
        @endif
    </div>
@endsection
