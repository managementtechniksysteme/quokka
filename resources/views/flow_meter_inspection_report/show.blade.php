@extends('layouts.app')

@section('content')
    <div class="q-container">

        @include('flow_meter_inspection_report.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Durchfluss-Prüfbericht · Anlage {{ $flowMeterInspectionReport->equipment_identifier }}</div>
                    <h1 class="q-title">{{ $flowMeterInspectionReport->project->name }}</h1>
                    <div class="q-meta">
                        <span class="q-status q-status--{{ $flowMeterInspectionReport->status }}">{{ $flowMeterInspectionReport->status_label }}</span>

                        <span class="q-chip">
                            @switch($flowMeterInspectionReport->status)
                                @case('signed')
                                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                                    {{ optional($signature)->created_at }}
                                    @break
                                @case('finished')
                                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                                    {{ $flowMeterInspectionReport->updated_at }}@if($flowMeterInspectionReport->activities->last()?->causer) · {{ Str::upper($flowMeterInspectionReport->activities->last()->causer->username) }}@endif
                                    @break
                                @default
                                    @if($flowMeterInspectionReport->signatureRequest)
                                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#send"></use></svg>
                                        {{ $flowMeterInspectionReport->signatureRequest->created_at }}
                                    @else
                                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                                        {{ $flowMeterInspectionReport->created_at }}
                                    @endif
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @unless($flowMeterInspectionReport->isFinished())
                    @can('approve', $flowMeterInspectionReport)
                        <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('flow-meter-inspection-reports.finish', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => 'show']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                            Erledigen
                        </a>
                    @endcan
                @endunless
                @can('update', $flowMeterInspectionReport)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('flow-meter-inspection-reports.edit', $flowMeterInspectionReport) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="flowMeterInspectionReportShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="flowMeterInspectionReportShowDropdown">
                        @can('create', \App\Models\FlowMeterInspectionReport::class)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.create', ['template' => $flowMeterInspectionReport]) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#files"></use></svg>
                                Kopieren
                            </a>
                        @endcan
                        @can('email', $flowMeterInspectionReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.email', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $flowMeterInspectionReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.download', $flowMeterInspectionReport) }}" target="_blank">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        @can('emailSignatureRequest', $flowMeterInspectionReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.email-signature-request', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Unterschrift Anfrage senden
                            </a>
                        @endcan
                        @can('emailDownloadRequest', $flowMeterInspectionReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.email-download-request', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#download"></use></svg>
                                Download Link senden
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $flowMeterInspectionReport)
                            <form action="{{ route('flow-meter-inspection-reports.destroy', $flowMeterInspectionReport) }}" method="post">
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
                <span class="q-statbar__label">Projekt</span>
                <span class="q-statbar__value text-truncate">
                    <a href="{{ route('projects.show', $flowMeterInspectionReport->project) }}">{{ $flowMeterInspectionReport->project->name }}</a>
                </span>
            </div>
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Techniker</span>
                <span class="q-statbar__value">{{ $flowMeterInspectionReport->employee->person->name }}</span>
            </div>
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Datum</span>
                <span class="q-statbar__value">{{ $flowMeterInspectionReport->inspected_on }}</span>
            </div>
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Wetter</span>
                <span class="q-statbar__value d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16">
                        @switch($flowMeterInspectionReport->weather)
                            @case('sunny')<use href="{{ asset('svg/bootstrap-icons.svg') }}#sun"></use>@break
                            @case('cloudy')<use href="{{ asset('svg/bootstrap-icons.svg') }}#cloud"></use>@break
                            @case('rainy')<use href="{{ asset('svg/bootstrap-icons.svg') }}#cloud-rain"></use>@break
                            @case('snowy')<use href="{{ asset('svg/bootstrap-icons.svg') }}#cloud-snow"></use>@break
                        @endswitch
                    </svg>
                    {{ __($flowMeterInspectionReport->weather) }} ({{ $flowMeterInspectionReport->temperature }} °C)
                </span>
            </div>
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Messstelle</span>
                <span class="q-statbar__value">{{ $flowMeterInspectionReport->measuring_point }}</span>
            </div>
        </div>

        @include('flow_meter_inspection_report._verdict')

        <div class="q-detail mt-3">
            <div class="d-flex flex-column gap-3">
            @include('flow_meter_inspection_report._content')

            {{-- PDF Anhang --}}
            @if($flowMeterInspectionReport->appendix())
                <div class="q-card">
                    <div class="q-card__head">PDF Anhang</div>
                    <div class="q-card__body">
                        <div class="row g-2">
                            <div class="col-12 col-md-6 col-lg-4">
                                <a href="{{ $flowMeterInspectionReport->appendix()->getUrl() }}" class="q-attach">
                                    <span class="q-attach__preview q-attach__preview--icon">
                                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#file-text"></use></svg>
                                    </span>
                                    <span class="min-w-0">
                                        <span class="q-attach__name text-truncate d-block">{{ $flowMeterInspectionReport->appendix()->file_name }}</span>
                                        <span class="q-attach__size">{{ $flowMeterInspectionReport->appendix()->human_readable_size }}</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Anhänge --}}
            @if($flowMeterInspectionReport->attachments()->count() > 0)
                <div class="q-card">
                    <div class="q-card__head">Anhänge</div>
                    <div class="q-card__body">
                        <div class="row g-2">
                            @foreach($flowMeterInspectionReport->attachments() as $attachment)
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
                        @can('sign', $flowMeterInspectionReport)
                            <a class="btn btn-primary text-white w-100 d-inline-flex align-items-center justify-content-center gap-2" href="{{ route('flow-meter-inspection-reports.sign', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => 'show']) }}">
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
