@extends('layouts.app')

@section('content')
    <div class="q-container">
        @if($additionsReport)
            <div class="q-page-head">
                <div class="d-flex align-items-center gap-3">
                    <span class="q-avatar">
                        <svg class="icon-bs icon-20"><use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use></svg>
                    </span>
                    <div>
                        <div class="q-eyebrow">Regiebericht · #{{ $additionsReport->number }}</div>
                        <h1 class="q-title">{{ $additionsReport->project->name }}</h1>
                    </div>
                </div>
            </div>

            <div class="q-statbar mb-4">
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Datum</span>
                    <span class="q-statbar__value">{{ $additionsReport->services_provided_on }}</span>
                </div>
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Regiestunden</span>
                    <span class="q-statbar__value">{{ Number::toLocal($additionsReport->hours) }}</span>
                </div>
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Wetter</span>
                    <span class="q-statbar__value d-inline-flex align-items-center gap-2">
                        <svg class="icon icon-16">
                            @switch($additionsReport->weather)
                                @case('sunny')<use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>@break
                                @case('cloudy')<use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cloud"></use>@break
                                @case('rainy')<use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cloud-rain"></use>@break
                                @case('snowy')<use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cloud-snow"></use>@break
                            @endswitch
                        </svg>
                        {{ __($additionsReport->weather) }} ({{ $additionsReport->minimum_temperature }}@if($additionsReport->minimum_temperature !== $additionsReport->maximum_temperature) bis {{ $additionsReport->maximum_temperature }}@endif °C)
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
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use></svg>
                            </span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Personalstand</div>
                                <div class="q-inforow__value">
                                    @forelse($additionsReport->involvedEmployees as $employee)
                                        {{ $employee->person->name }}@unless($loop->last)<br />@endunless
                                    @empty
                                        <span class="q-inforow__value--empty">nicht angegeben</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        @if($additionsReport->presentPeople->count())
                            <div class="q-inforow">
                                <span class="q-inforow__icon">
                                    <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use></svg>
                                </span>
                                <div class="q-inforow__main">
                                    <div class="q-inforow__label">Anwesende Personen</div>
                                    <div class="q-inforow__value">
                                        @foreach($additionsReport->presentPeople as $person)
                                            {{ $person->name }}@unless($loop->last)<br />@endunless
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($additionsReport->other_visitors)
                            <div class="q-inforow">
                                <span class="q-inforow__icon">
                                    <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use></svg>
                                </span>
                                <div class="q-inforow__main">
                                    <div class="q-inforow__label">Sonstige Besucher</div>
                                    <div class="q-inforow__value">{{ $additionsReport->other_visitors }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Beeinflussende Faktoren --}}
                @if ($additionsReport->has_influencing_factors)
                    <div class="q-card">
                        <div class="q-card__head">Beeinflussende Faktoren</div>
                        <div class="q-card__body d-flex flex-column gap-3">
                            @if ($additionsReport->inspection_comment)
                                <div>
                                    <div class="q-section-label">Güte- und Funktionsprüfung</div>
                                    <div>{{ $additionsReport->inspection_comment }}</div>
                                </div>
                            @endif
                            @if ($additionsReport->missing_documents)
                                <div>
                                    <div class="q-section-label">Fehlende Ausführungsunterlagen</div>
                                    <div>{{ $additionsReport->missing_documents }}</div>
                                </div>
                            @endif
                            @if ($additionsReport->special_occurrences)
                                <div>
                                    <div class="q-section-label">Besondere Vorkommnisse</div>
                                    <div>{{ $additionsReport->special_occurrences }}</div>
                                </div>
                            @endif
                            @if ($additionsReport->imminent_danger)
                                <div>
                                    <div class="q-section-label">Gefahr in Verzug</div>
                                    <div>{{ $additionsReport->imminent_danger }}</div>
                                </div>
                            @endif
                            @if ($additionsReport->concerns)
                                <div>
                                    <div class="q-section-label">Bedenken</div>
                                    <div>{{ $additionsReport->concerns }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if ($additionsReport->comment)
                    <div class="q-card">
                        <div class="q-card__head">Leistungsfortschritt</div>
                        <div class="q-card__body">
                            <div class="markdown">
                                {!! Html::fromMarkdown($additionsReport->comment) !!}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Unterschreiben --}}
                <div class="q-card">
                    <div class="q-card__head">Regiebericht unterschreiben</div>
                    <div class="q-card__body">
                        <div class="q-banner q-banner--info">
                            <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use></svg>
                            <span>Der Regiebericht kann nach erfolgreicher Unterschrift heruntergeladen werden.</span>
                        </div>

                        <p class="text-muted">
                            Unterschreiben Sie bitte in folgendem Feld.<br />
                            Am Computer unterschreiben Sie mit der Maus, indem Sie die linke Maustaste gedrückt halten. Am Mobiltelefon, Tablet oder anderen Geräten mit Touchscreen benutzen Sie Ihren Finger oder einen für Ihr Gerät passenden Eingabestift.<br />
                            Klicken Sie danach auf den <strong>Regiebericht unterschreiben</strong> Button. Mit dem <strong>Zurücksetzen</strong> Button können Sie die Eingabe löschen.
                        </p>

                        <form action="{{ route('additions-reports.customer-sign', $additionsReport->signatureRequest->token) }}" method="post">
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
                                    Regiebericht unterschreiben
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
            @include('additions_report.sign_invalid_content')
        @endif
    </div>
@endsection
