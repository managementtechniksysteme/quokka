@extends('layouts.app')

@section('content')
    <div class="q-container">
        @if($constructionReport)
            <div class="q-page-head">
                <div class="d-flex align-items-center gap-3">
                    <span class="q-avatar">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use></svg>
                    </span>
                    <div>
                        <div class="q-eyebrow">Bautagesbericht · #{{ $constructionReport->number }}</div>
                        <h1 class="q-title">{{ $constructionReport->project->name }}</h1>
                    </div>
                </div>
            </div>

            <div class="q-statbar mb-4">
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Datum</span>
                    <span class="q-statbar__value">{{ $constructionReport->services_provided_on }}</span>
                </div>
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Wetter</span>
                    <span class="q-statbar__value d-inline-flex align-items-center gap-2">
                        <svg class="icon-bs icon-16">
                            @switch($constructionReport->weather)
                                @case('sunny')<use href="{{ asset('svg/bootstrap-icons.svg') }}#sun"></use>@break
                                @case('cloudy')<use href="{{ asset('svg/bootstrap-icons.svg') }}#cloud"></use>@break
                                @case('rainy')<use href="{{ asset('svg/bootstrap-icons.svg') }}#cloud-rain"></use>@break
                                @case('snowy')<use href="{{ asset('svg/bootstrap-icons.svg') }}#cloud-snow"></use>@break
                            @endswitch
                        </svg>
                        {{ __($constructionReport->weather) }} ({{ $constructionReport->minimum_temperature }}@if($constructionReport->minimum_temperature !== $constructionReport->maximum_temperature) bis {{ $constructionReport->maximum_temperature }}@endif °C)
                    </span>
                </div>
            </div>

            <div class="d-flex flex-column gap-3">
                {{-- Beteiligte --}}
                <div class="q-card">
                    <div class="q-card__head">Beteiligte</div>
                    <div class="q-card__body">
                        <div class="q-inforow">
                            <span class="q-inforow__icon">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use></svg>
                            </span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Personalstand</div>
                                <div class="q-inforow__value">
                                    @forelse($constructionReport->involvedEmployees as $employee)
                                        {{ $employee->person->name }}@unless($loop->last)<br />@endunless
                                    @empty
                                        <span class="q-inforow__value--empty">nicht angegeben</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        @if($constructionReport->presentPeople->count())
                            <div class="q-inforow">
                                <span class="q-inforow__icon">
                                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use></svg>
                                </span>
                                <div class="q-inforow__main">
                                    <div class="q-inforow__label">Anwesende Personen</div>
                                    <div class="q-inforow__value">
                                        @foreach($constructionReport->presentPeople as $person)
                                            {{ $person->name }}@unless($loop->last)<br />@endunless
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($constructionReport->other_visitors)
                            <div class="q-inforow">
                                <span class="q-inforow__icon">
                                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use></svg>
                                </span>
                                <div class="q-inforow__main">
                                    <div class="q-inforow__label">Sonstige Besucher</div>
                                    <div class="q-inforow__value">{{ $constructionReport->other_visitors }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Beeinflussende Faktoren --}}
                @if ($constructionReport->has_influencing_factors)
                    <div class="q-card">
                        <div class="q-card__head">Beeinflussende Faktoren</div>
                        <div class="q-card__body d-flex flex-column gap-3">
                            @if ($constructionReport->inspection_comment)
                                <div>
                                    <div class="q-section-label">Güte- und Funktionsprüfung</div>
                                    <div>{{ $constructionReport->inspection_comment }}</div>
                                </div>
                            @endif
                            @if ($constructionReport->missing_documents)
                                <div>
                                    <div class="q-section-label">Fehlende Ausführungsunterlagen</div>
                                    <div>{{ $constructionReport->missing_documents }}</div>
                                </div>
                            @endif
                            @if ($constructionReport->special_occurrences)
                                <div>
                                    <div class="q-section-label">Besondere Vorkommnisse</div>
                                    <div>{{ $constructionReport->special_occurrences }}</div>
                                </div>
                            @endif
                            @if ($constructionReport->imminent_danger)
                                <div>
                                    <div class="q-section-label">Gefahr in Verzug</div>
                                    <div>{{ $constructionReport->imminent_danger }}</div>
                                </div>
                            @endif
                            @if ($constructionReport->concerns)
                                <div>
                                    <div class="q-section-label">Bedenken</div>
                                    <div>{{ $constructionReport->concerns }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if ($constructionReport->comment)
                    <div class="q-card">
                        <div class="q-card__head">Leistungsfortschritt</div>
                        <div class="q-card__body">
                            <div class="markdown">
                                {!! Html::fromMarkdown($constructionReport->comment) !!}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Unterschreiben --}}
                <div class="q-card">
                    <div class="q-card__head">Bautagesbericht unterschreiben</div>
                    <div class="q-card__body">
                        <div class="q-banner q-banner--info">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
                            <span>Der Bautagesbericht kann nach erfolgreicher Unterschrift heruntergeladen werden.</span>
                        </div>

                        <p class="text-muted">
                            Unterschreiben Sie bitte in folgendem Feld.<br />
                            Am Computer unterschreiben Sie mit der Maus, indem Sie die linke Maustaste gedrückt halten. Am Mobiltelefon, Tablet oder anderen Geräten mit Touchscreen benutzen Sie Ihren Finger oder einen für Ihr Gerät passenden Eingabestift.<br />
                            Klicken Sie danach auf den <strong>Bautagesbericht unterschreiben</strong> Button. Mit dem <strong>Zurücksetzen</strong> Button können Sie die Eingabe löschen.
                        </p>

                        <form action="{{ route('construction-reports.customer-sign', $constructionReport->signatureRequest->token) }}" method="post">
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
                                    <span class="d-none d-md-inline">Bautagesbericht unterschreiben</span>
                                    <span class="d-inline d-md-none">Unterschreiben</span>
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
            @include('construction_report.sign_invalid_content')
        @endif
    </div>
@endsection
