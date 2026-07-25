@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('inspection-reports.index') }}" class="q-appbar__btn" aria-label="Zurück zu Prüfberichten">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title">Anlage {{ $inspectionReport->equipment_identifier }}</span>
    <button class="q-appbar__btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#inspectionReportShowActionsSheet" aria-controls="inspectionReportShowActionsSheet" aria-label="Aktionen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
    </button>
@endsection

@section('mobile-detail-sheets')
    <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="inspectionReportShowActionsSheet" aria-label="Aktionen">
        <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
        <div class="offcanvas-body">
            <div class="q-sheet__label">Aktionen</div>
            @unless($inspectionReport->isFinished())
                @can('approve', $inspectionReport)
                    <a class="q-row" href="{{ route('inspection-reports.finish', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg></span>
                        <span class="q-row__title">Erledigen</span>
                    </a>
                @endcan
            @endunless
            @can('update', $inspectionReport)
                <a class="q-row" href="{{ route('inspection-reports.edit', $inspectionReport) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg></span>
                    <span class="q-row__title">Bearbeiten</span>
                </a>
            @endcan
            @can('create', \App\Models\InspectionReport::class)
                <a class="q-row" href="{{ route('inspection-reports.create', ['template' => $inspectionReport]) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#files"></use></svg></span>
                    <span class="q-row__title">Kopieren</span>
                </a>
            @endcan
            @can('email', $inspectionReport)
                <a class="q-row" href="{{ route('inspection-reports.email', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg></span>
                    <span class="q-row__title">Email versenden</span>
                </a>
            @endcan
            @can('createPdf', $inspectionReport)
                <a class="q-row" href="{{ route('inspection-reports.download', $inspectionReport) }}" target="_blank">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg></span>
                    <span class="q-row__title">PDF erstellen</span>
                </a>
            @endcan
            @can('emailSignatureRequest', $inspectionReport)
                <a class="q-row" href="{{ route('inspection-reports.email-signature-request', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg></span>
                    <span class="q-row__title">Unterschrift Anfrage senden</span>
                </a>
            @endcan
            @can('emailDownloadRequest', $inspectionReport)
                <a class="q-row" href="{{ route('inspection-reports.email-download-request', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#download"></use></svg></span>
                    <span class="q-row__title">Download Link senden</span>
                </a>
            @endcan
            <a class="q-row" href="#">
                <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg></span>
                <span class="q-row__title">Favorisieren</span>
            </a>
            @can('delete', $inspectionReport)
                <form action="{{ route('inspection-reports.destroy', $inspectionReport) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="q-row q-row--danger">
                        <span class="q-avatar q-avatar--danger"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg></span>
                        <span class="q-row__title">Entfernen</span>
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="q-container">

        <div class="d-none d-md-block">
            @include('inspection_report.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Prüfbericht · Anlage {{ $inspectionReport->equipment_identifier }}</div>
                    <h1 class="q-title">{{ $inspectionReport->project->name }}</h1>
                    <div class="q-meta">
                        <span class="q-status q-status--{{ $inspectionReport->status }}">{{ $inspectionReport->status_label }}</span>

                        <span class="q-chip">
                            @switch($inspectionReport->status)
                                @case('signed')
                                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                                    {{ optional($signature)->created_at }}
                                    @break
                                @case('finished')
                                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                                    {{ $inspectionReport->updated_at }}@if($inspectionReport->activities->last()?->causer) · {{ Str::upper($inspectionReport->activities->last()->causer->username) }}@endif
                                    @break
                                @default
                                    @if($inspectionReport->signatureRequest)
                                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#send"></use></svg>
                                        {{ $inspectionReport->signatureRequest->created_at }}
                                    @else
                                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                                        {{ $inspectionReport->created_at }}
                                    @endif
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @unless($inspectionReport->isFinished())
                    @can('approve', $inspectionReport)
                        <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('inspection-reports.finish', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                            Erledigen
                        </a>
                    @endcan
                @endunless
                @can('update', $inspectionReport)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('inspection-reports.edit', $inspectionReport) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="inspectionReportShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="inspectionReportShowDropdown">
                        @can('create', \App\Models\InspectionReport::class)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.create', ['template' => $inspectionReport]) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#files"></use></svg>
                                Kopieren
                            </a>
                        @endcan
                        @can('email', $inspectionReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.email', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $inspectionReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.download', $inspectionReport) }}" target="_blank">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        @can('emailSignatureRequest', $inspectionReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.email-signature-request', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Unterschrift Anfrage senden
                            </a>
                        @endcan
                        @can('emailDownloadRequest', $inspectionReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.email-download-request', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#download"></use></svg>
                                Download Link senden
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $inspectionReport)
                            <form action="{{ route('inspection-reports.destroy', $inspectionReport) }}" method="post">
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

        {{-- Mobile-only: .q-page-head's own .q-meta (status + timestamp chip)
             is hidden along with the rest of the desktop head above
             (2026-07-21, same fix as the other modules'). --}}
        <div class="q-meta d-flex d-md-none mt-2 pt-1 mb-3">
            <span class="q-status q-status--{{ $inspectionReport->status }}">{{ $inspectionReport->status_label }}</span>

            <span class="q-chip">
                @switch($inspectionReport->status)
                    @case('signed')
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                        {{ optional($signature)->created_at }}
                        @break
                    @case('finished')
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                        {{ $inspectionReport->updated_at }}@if($inspectionReport->activities->last()?->causer) · {{ Str::upper($inspectionReport->activities->last()->causer->username) }}@endif
                        @break
                    @default
                        @if($inspectionReport->signatureRequest)
                            <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#send"></use></svg>
                            {{ $inspectionReport->signatureRequest->created_at }}
                        @else
                            <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            {{ $inspectionReport->created_at }}
                        @endif
                @endswitch
            </span>
        </div>

        <div class="q-statbar mb-4">
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Projekt</span>
                <span class="q-statbar__value text-truncate">
                    <a href="{{ route('projects.show', $inspectionReport->project) }}">{{ $inspectionReport->project->name }}</a>
                </span>
            </div>
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
                    <svg class="icon-bs icon-16">
                        @switch($inspectionReport->weather)
                            @case('sunny')<use href="{{ asset('svg/bootstrap-icons.svg') }}#sun"></use>@break
                            @case('cloudy')<use href="{{ asset('svg/bootstrap-icons.svg') }}#cloud"></use>@break
                            @case('rainy')<use href="{{ asset('svg/bootstrap-icons.svg') }}#cloud-rain"></use>@break
                            @case('snowy')<use href="{{ asset('svg/bootstrap-icons.svg') }}#cloud-snow"></use>@break
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

        <div class="q-detail">
            <div class="d-flex flex-column gap-3">
            {{-- Messwerte --}}
            @include('inspection_report._measurements')

            {{-- Durchgeführte Arbeiten und Bemerkungen --}}
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

            {{-- Anhänge --}}
            @if($inspectionReport->attachments()->count() > 0)
                <div class="q-card">
                    <div class="q-card__head">Anhänge</div>
                    <div class="q-card__body">
                        <div class="row g-2">
                            @foreach($inspectionReport->attachments() as $attachment)
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
                        @can('sign', $inspectionReport)
                            <a class="btn btn-primary text-white w-100 d-inline-flex align-items-center justify-content-center gap-2" href="{{ route('inspection-reports.sign', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                                Unterschreiben lassen
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
