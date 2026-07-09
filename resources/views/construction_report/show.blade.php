@extends('layouts.app')

@section('content')
    <div class="q-container">

        @include('construction_report.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Bautagesbericht · #{{ $constructionReport->number }}</div>
                    <h1 class="q-title">{{ $constructionReport->project->name }}</h1>
                    <div class="q-meta">
                        <span class="q-status q-status--{{ $constructionReport->status }}">{{ $constructionReport->status_label }}</span>

                        <span class="q-chip">
                            @switch($constructionReport->status)
                                @case('signed')
                                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                                    {{ optional($signature)->created_at }}
                                    @break
                                @case('finished')
                                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                                    {{ $constructionReport->updated_at }}@if($constructionReport->activities->last()?->causer) · {{ Str::upper($constructionReport->activities->last()->causer->username) }}@endif
                                    @break
                                @default
                                    @if($constructionReport->signatureRequest)
                                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#send"></use></svg>
                                        {{ $constructionReport->signatureRequest->created_at }}
                                    @else
                                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                                        {{ $constructionReport->created_at }}
                                    @endif
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @unless($constructionReport->isFinished())
                    @can('approve', $constructionReport)
                        <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('construction-reports.finish', ['construction_report' => $constructionReport, 'redirect' => 'show']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                            Erledigen
                        </a>
                    @endcan
                @endunless
                @can('update', $constructionReport)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('construction-reports.edit', $constructionReport) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="constructionReportShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="constructionReportShowDropdown">
                        @can('email', $constructionReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.email', ['construction_report' => $constructionReport, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $constructionReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.download', $constructionReport) }}" target="_blank">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        @can('emailSignatureRequest', $constructionReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.email-signature-request', ['construction_report' => $constructionReport, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Unterschrift Anfrage senden
                            </a>
                        @endcan
                        @can('emailDownloadRequest', $constructionReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.email-download-request', ['construction_report' => $constructionReport, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#download"></use></svg>
                                Download Link senden
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $constructionReport)
                            <form action="{{ route('construction-reports.destroy', $constructionReport) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                                    Entfernen
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="q-statbar mb-4">
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Bauvorhaben</span>
                <span class="q-statbar__value text-truncate">
                    <a href="{{ route('projects.show', $constructionReport->project) }}">{{ $constructionReport->project->name }}</a>
                </span>
            </div>
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Ersteller</span>
                <span class="q-statbar__value">{{ $constructionReport->employee->person->name }}</span>
            </div>
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

        <div class="q-detail">
            <div class="d-flex flex-column gap-3">
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

                {{-- Leistungsfortschritt --}}
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

                {{-- Anhänge --}}
                @if($constructionReport->attachments()->count() > 0)
                    <div class="q-card">
                        <div class="q-card__head">Anhänge</div>
                        <div class="q-card__body">
                            <div class="row g-2">
                                @foreach($constructionReport->attachments() as $attachment)
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <a href="{{ $attachment->getUrl() }}" class="q-attach">
                                            @if($attachment->hasGeneratedConversion('thumbnail'))
                                                <img class="q-attach__preview" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                            @else
                                                <span class="q-attach__preview q-attach__preview--icon">
                                                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#file-text"></use></svg>
                                                </span>
                                            @endif
                                            <span class="min-w-0">
                                                <span class="q-attach__name text-truncate d-block">{{ $attachment->file_name }}</span>
                                                <span class="q-attach__size">{{ $attachment->human_readable_size }}</span>
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-flex flex-column gap-3">
                {{-- Beteiligte Personen --}}
                <div class="q-card q-card--quiet">
                    <div class="q-card__body">
                    <div class="q-aside__group">
                        <div class="q-aside__label">Personalstand · {{ $constructionReport->involvedEmployees->count() }}</div>
                        @forelse($constructionReport->involvedEmployees as $employee)
                            <div class="q-aside__person">
                                @include('partials.employee_avatar', ['employee' => $employee, 'modifier' => 'q-avatar--sm'])
                                <span class="q-aside__name text-truncate">{{ $employee->person->name }}</span>
                            </div>
                        @empty
                            <div class="q-aside__person q-aside__person--muted">
                                <span class="q-aside__name">nicht angegeben</span>
                            </div>
                        @endforelse
                    </div>

                    @if($constructionReport->presentPeople->count())
                        <div class="q-aside__group">
                            <div class="q-aside__label">Anwesende Personen · {{ $constructionReport->presentPeople->count() }}</div>
                            @foreach($constructionReport->presentPeople as $person)
                                <div class="q-aside__person">
                                    <span class="q-avatar q-avatar--round q-avatar--sm q-avatar--{{ $person->avatar_colour }}">{{ $person->initials }}</span>
                                    <span class="q-aside__name text-truncate">{{ $person->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($constructionReport->other_visitors)
                        <div class="q-aside__group">
                            <div class="q-aside__label">Sonstige Besucher</div>
                            <div class="q-aside__person q-aside__person--muted">
                                <span class="q-aside__name">{{ $constructionReport->other_visitors }}</span>
                            </div>
                        </div>
                    @endif
                    </div>
                </div>

                {{-- Unterschrift --}}
                <div class="q-card q-card--quiet">
                <div class="q-card__head d-flex align-items-center justify-content-between">
                    <span>Unterschrift</span>
                    @if($signature)
                        <span class="q-chip q-chip--success">
                            <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                            vorhanden
                        </span>
                    @else
                        <span class="q-chip q-chip--warning">
                            <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clock"></use></svg>
                            ausstehend
                        </span>
                    @endif
                </div>
                <div class="q-card__body">
                    @if($signature)
                        <img class="q-sign-img" src="{{ $signature->getUrl() }}" alt="Unterschrift" />
                        <div class="q-sign-date">unterschrieben am {{ $signature->created_at }}</div>
                    @else
                        <div class="q-placeholder">
                            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                            Keine Unterschrift
                        </div>
                        @can('sign', $constructionReport)
                            <a class="btn btn-primary text-white w-100 d-inline-flex align-items-center justify-content-center gap-2" href="{{ route('construction-reports.sign', ['construction_report' => $constructionReport, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                                Unterschreiben lassen
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
