@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('inspection_report.breadcrumb')

            <h3>
                Prüfbericht
                <small class="text-muted">Anlage {{ $inspectionReport->equipment_identifier }} (Projekt {{ $inspectionReport->project->name }}) vom {{ $inspectionReport->inspected_on }}
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @unless($inspectionReport->isFinished())
                    @can('approve', $inspectionReport)
                        <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('inspection-reports.finish', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            Erledigen
                        </a>
                    @endcan
                @endunless
                @can('update', $inspectionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('inspection-reports.edit', $inspectionReport) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('create', \App\Models\InspectionReport::class)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('inspection-reports.create', ['template' => $inspectionReport]) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#copy"></use>
                        </svg>
                        Kopieren
                    </a>
                @endcan
                @can('email', $inspectionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('inspection-reports.email', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $inspectionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('inspection-reports.download', $inspectionReport) }}" target="_blank">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                @endcan
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                @can('sign', $inspectionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('inspection-reports.sign', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                        </svg>
                        Unterschreiben lassen
                    </a>
                @endcan
                @can('emailSignatureRequest', $inspectionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('inspection-reports.email-signature-request', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Unterschrift Anfrage senden
                    </a>
                @endcan
                @can('emailDownloadRequest', $inspectionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('inspection-reports.email-download-request', ['inspection_report' => $inspectionReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use>
                        </svg>
                        Download Link senden
                    </a>
                @endcan
                @can('delete', $inspectionReport)
                    <form action="{{ route('inspection-reports.destroy', $inspectionReport) }}" method="post" >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                @endcan
            </div>

        </div>
    </div>

    <div class="container my-4">
        <div class="row">
            <div class="col-sm-auto">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                    </svg>
                    Projekt
                </div>
            </div>
            <div class="col-sm">
                <a href="{{ route('projects.show', $inspectionReport->project) }}">{{ $inspectionReport->project->name }}</a>
            </div>
        </div>

        <div class="row mt-3 mt-md-4">
            <div class="col-sm">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                            </svg>
                            Techniker
                        </div>
                    </div>
                    <div class="col-lg-6">
                        {{ $inspectionReport->employee->person->name }}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-6">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="@if($inspectionReport->isNew()) text-primary @elseif($inspectionReport->isSigned()) text-warning @else text-success @endif icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#git-commit"></use>
                            </svg>
                            Status
                        </div>
                    </div>
                    <div class="col-lg-6">
                        {{ __($inspectionReport->status) }}
                        @switch($inspectionReport->status)
                            @case('new')
                                @if($inspectionReport->signatureRequest)
                                    (Anfrage zur Unterschrift gesendet am {{ $inspectionReport->signatureRequest->created_at }})
                                @else
                                    (erstellt am {{ $inspectionReport->created_at }})
                                @endif
                                @break
                            @case('signed')
                                am {{ $inspectionReport->signature()->created_at }}
                                @break
                            @case('finished')
                                am {{ $inspectionReport->updated_at }}
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-6">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                            </svg>
                            Datum
                        </div>
                    </div>
                    <div class="col-lg-6">
                        {{ $inspectionReport->inspected_on }}
                    </div>
                </div>
            </div>

            <div class="col-sm mt-3 mt-sm-0">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                @switch($inspectionReport->weather)
                                    @case('sunny')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>
                                        @break
                                    @case('cloudy')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cloud"></use>
                                        @break
                                    @case('rainy')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cloud-rain"></use>
                                        @break
                                    @case('snowy')
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cloud-snow"></use>
                                        @break
                                @endswitch
                            </svg>
                            Wetter
                        </div>
                    </div>
                    <div class="col-lg-6">
                        {{ __($inspectionReport->weather) }}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-6">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#list"></use>
                            </svg>
                            Anlagentyp
                        </div>
                    </div>
                    <div class="col-lg-6">
                        {{ $inspectionReport->equipment_type }}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-6">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#hash"></use>
                            </svg>
                            Anlagen-/Gerätenummer
                        </div>
                    </div>
                    <div class="col-lg-6">
                        {{ $inspectionReport->equipment_identifier }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm">
                <div class="row">
                    <div class="col">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>
                            </svg>
                            <span class="font-weight-bolder">UVC Strahler</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        Anzahl, Typ
                    </div>
                    <div class="col-lg-6">
                        {{ $inspectionReport->uvc_lamp_quantity }} x {{ $inspectionReport->uvc_lamp_type }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        Betriebsstunden
                    </div>
                    <div class="col-lg-6">
                        {{ Number::toLocal($inspectionReport->uvc_lamp_operating_hours) }}h
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        Impulse
                    </div>
                    <div class="col-lg-6">
                        {{ Number::toLocal($inspectionReport->uvc_lamp_impulses) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        UV Intensität bei Ankunft, Abfahrt
                    </div>
                    <div class="col-lg-6">
                        {{ Number::toLocal($inspectionReport->uvc_lamp_uv_intensity_arrival) }}{{ $inspectionReport->uvc_lamp_values_unit_string }},
                        {{ Number::toLocal($inspectionReport->uvc_lamp_uv_intensity_departure) }}{{ $inspectionReport->uvc_lamp_values_unit_string }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        Ersatzstrahler vorhanden
                    </div>
                    <div class="col-lg-6">
                        {{ $inspectionReport->uvc_lamp_replacement_available_string }}
                    </div>
                </div>
            </div>
            <div class="col-sm mt-4 mt-sm-0">
                <div class="row">
                    <div class="col">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#activity"></use>
                            </svg>
                            <span class="font-weight-bolder">UVC Sensor</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        Typ
                    </div>
                    <div class="col-lg-6">
                        {{ $inspectionReport->uvc_sensor_type }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        Seriennummer
                    </div>
                    <div class="col-lg-6">
                        {{ $inspectionReport->uvc_sensor_identifier }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        Voralarm
                    </div>
                    <div class="col-lg-6">
                        {{ Number::toLocal($inspectionReport->uvc_sensor_pre_alarm) }}{{ $inspectionReport->uvc_sensor_values_unit_string }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        Abschaltpunkt
                    </div>
                    <div class="col-lg-6">
                        {{ Number::toLocal($inspectionReport->uvc_sensor_cut_off_point) }}{{ $inspectionReport->uvc_sensor_values_unit_string }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm">
                <div class="row">
                    <div class="col">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#droplet"></use>
                            </svg>
                            <span class="font-weight-bolder">Wasser</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        Durchfluss
                    </div>
                    <div class="col-lg-6">
                        {{ Number::toLocal($inspectionReport->water_flow_rate) }} m³/h
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        min<span class="d-lg-none">imale</span><span class="d-none d-lg-inline">.</span>, gem<span class="d-lg-none">essene</span><span class="d-none d-lg-inline">.</span> Trans<span class="d-lg-none d-xl-inline">mission</span><span class="d-none d-lg-inline d-xl-none">. </span>[100mm]
                    </div>
                    <div class="col-lg-6">
                        {{ Number::toLocal($inspectionReport->water_minimum_uv_transmission) }}%,
                        {{ Number::toLocal($inspectionReport->water_measured_uv_transmission) }}%
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        Schwebestoffe sichtbar
                    </div>
                    <div class="col-lg-6">
                        {{ $inspectionReport->water_suspended_load_visible_string }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        Luftblasenfrei
                    </div>
                    <div class="col-lg-6">
                        {{ $inspectionReport->water_air_bubble_free_string }}
                    </div>
                </div>
            </div>
            <div class="col-sm mt-4 mt-sm-0">
                <div class="row">
                    <div class="col">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#circle"></use>
                            </svg>
                            <span class="font-weight-bolder"><span class="d-inline d-sm-none d-md-inline">Überprüfung der </span>Quarzschutzrohre</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        Verschmutzung
                    </div>
                    <div class="col-lg-6">
                        {{ $inspectionReport->quartz_tube_contaminated_string }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-muted">
                        Undicht
                    </div>
                    <div class="col-lg-6">
                        {{ $inspectionReport->quartz_tube_leaking_string }}
                    </div>
                </div>
            </div>
        </div>

        @if ($inspectionReport->comment)
            <div class="text-muted d-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                </svg>
                Durchgeführte Arbeiten und Bemerkungen
            </div>
            {!! Html::fromMarkdown($inspectionReport->comment) !!}
        @endif

        @if($inspectionReport->attachments()->count() > 0)
            <div class="row text-muted d-flex align-items-center mt-1">
                <div class="col">
                    <div class="d-none d-md-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                        </svg>
                        Anhänge
                    </div>
                    <a class="d-inline-flex d-md-none d-inline-flex align-items-center" data-toggle="collapse" href="#collapseInspectionReportAttachments-{{ $inspectionReport->id }}" role="button" aria-expanded="false" aria-controls="collapseInspectionReportAttachments-{{ $inspectionReport->id }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                        </svg>
                        Anhänge
                    </a>
                </div>
            </div>
            <div class="d-none d-md-block">
                <div class="row">
                    @foreach($inspectionReport->attachments() as $attachment)
                        <div class="col-12 col-md-6 col-lg-3 mt-1">
                            <div class="attachment bg-gray-100 border border-gray-300 d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                                @if($attachment->hasGeneratedConversion('thumbnail'))
                                    <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                @else
                                    <svg class="icon attachment-img-preview mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use>
                                    </svg>
                                @endif
                                <div class="min-w-0">
                                    <div class="min-w-0 text-truncate">{{ $attachment->file_name }}</div>
                                    <div class="text-muted">{{ $attachment->human_readable_size }}</div>
                                </div>
                                <a href="{{ $attachment->getUrl() }}" class="stretched-link outline-none"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="collapse d-md-none" id="collapseInspectionReportAttachments-{{ $inspectionReport->id }}">
                <div class="row">
                    @foreach($inspectionReport->attachments() as $attachment)
                        <div class="col-12 col-md-6 col-lg-3 mt-1">
                            <div class="attachment bg-gray-100 border border-gray-300 d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                                @if($attachment->hasGeneratedConversion('thumbnail'))
                                    <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                @else
                                    <svg class="icon attachment-img-preview mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use>
                                    </svg>
                                @endif
                                <div class="min-w-0">
                                    <div class="min-w-0 text-truncate">{{ $attachment->file_name }}</div>
                                    <div class="text-muted">{{ $attachment->human_readable_size }}</div>
                                </div>
                                <a href="{{ $attachment->getUrl() }}" class="stretched-link outline-none"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

@endsection
