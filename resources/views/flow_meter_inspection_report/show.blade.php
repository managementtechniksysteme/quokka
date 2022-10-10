@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('flow_meter_inspection_report.breadcrumb')

            <h3>
                Prüfbericht für Durchflussmesseinrichtungen
                <small class="text-muted">Anlage {{ $flowMeterInspectionReport->equipment_identifier }} (Projekt {{ $flowMeterInspectionReport->project->name }}) vom {{ $flowMeterInspectionReport->inspected_on }}
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @unless($flowMeterInspectionReport->isFinished())
                    @can('approve', $flowMeterInspectionReport)
                        <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.finish', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => 'show']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            Erledigen
                        </a>
                    @endcan
                @endunless
                @can('update', $flowMeterInspectionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.edit', $flowMeterInspectionReport) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('create', \App\Models\FlowMeterInspectionReport::class)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.create', ['template' => $flowMeterInspectionReport]) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#copy"></use>
                        </svg>
                        Kopieren
                    </a>
                @endcan
                @can('email', $flowMeterInspectionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.email', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $flowMeterInspectionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.download', $flowMeterInspectionReport) }}" target="_blank">
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
                @can('sign', $flowMeterInspectionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.sign', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                        </svg>
                        Unterschreiben lassen
                    </a>
                @endcan
                @can('emailSignatureRequest', $flowMeterInspectionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.email-signature-request', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Unterschrift Anfrage senden
                    </a>
                @endcan
                @can('emailDownloadRequest', $flowMeterInspectionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.email-download-request', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use>
                        </svg>
                        Download Link senden
                    </a>
                @endcan
                @can('delete', $flowMeterInspectionReport)
                    <form action="{{ route('flow-meter-inspection-reports.destroy', $flowMeterInspectionReport) }}" method="post" >
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
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                    </svg>
                    Projekt
                </div>
            </div>
            <div class="col">
                <a href="{{ route('projects.show', $flowMeterInspectionReport->project) }}">{{ $flowMeterInspectionReport->project->name }}</a>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                    </svg>
                    Techniker
                </div>
            </div>
            <div class="col">
                {{ $flowMeterInspectionReport->employee->person->name }}
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="@if($flowMeterInspectionReport->isNew()) text-primary @elseif($flowMeterInspectionReport->isSigned()) text-warning @else text-success @endif icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#git-commit"></use>
                    </svg>
                    Status
                </div>
            </div>
            <div class="col">
                {{ __($flowMeterInspectionReport->status) }}
                @switch($flowMeterInspectionReport->status)
                    @case('new')
                        @if($flowMeterInspectionReport->signatureRequest)
                            (Anfrage zur Unterschrift gesendet am {{ $flowMeterInspectionReport->signatureRequest->created_at }})
                        @else
                            (erstellt am {{ $flowMeterInspectionReport->created_at }})
                        @endif
                        @break
                    @case('signed')
                        am {{ $flowMeterInspectionReport->signature()->created_at }}
                        @break
                    @case('finished')
                        am {{ $flowMeterInspectionReport->updated_at }}
                        @if($flowMeterInspectionReport->activities->last())
                            ({{ Str::upper($flowMeterInspectionReport->activities->last()->causer->username) }})
                        @endif
                        @break
                @endswitch
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                    </svg>
                    Datum
                </div>
            </div>
            <div class="col">
                {{ $flowMeterInspectionReport->inspected_on }}
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        @switch($flowMeterInspectionReport->weather)
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
            <div class="col">
                {{ __($flowMeterInspectionReport->weather) }} ({{ $flowMeterInspectionReport->temperature }} °C)
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon-bs icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#building"></use>
                    </svg>
                    Anlage
                </div>
            </div>
            <div class="col">
                {{ $flowMeterInspectionReport->equipment_identifier }}
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon-bs icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#building"></use>
                    </svg>
                    Messstelle
                </div>
            </div>
            <div class="col">
                {{ $flowMeterInspectionReport->measuring_point }}
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon-bs icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-left-right"></use>
                    </svg>
                    Abweichung Messwerte
                </div>
            </div>
            <div class="col">
                von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub>: {{ $flowMeterInspectionReport->measurement_difference_up_to_30_q_max }}, über 0,3 Q<sub>max</sub>: {{ $flowMeterInspectionReport->measurement_difference_above_30_q_max }}
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-3">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon-bs icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-left-right"></use>
                    </svg>
                    Abweichung Zählerstände
                </div>
            </div>
            <div class="col">
                von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub>: {{ $flowMeterInspectionReport->reading_difference_up_to_30_q_max }}, über 0,3 Q<sub>max</sub>: {{ $flowMeterInspectionReport->reading_difference_above_30_q_max }}
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="d-flex align-items-center @if($flowMeterInspectionReport->equipment_in_tolerance_range) text-success @else text-danger @endif">
                    <svg class="icon icon-16 mr-2">
                        @if($flowMeterInspectionReport->equipment_in_tolerance_range)
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check"></use>
                        @else
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x"></use>
                        @endif
                    </svg>
                    Das Messystem arbeitet {{ $flowMeterInspectionReport->equipment_in_tolerance_range ? 'innerhalb' : 'außerhalb' }} des Toleranzbereichs des ÖWAV Regelblatts 38
                </div>
            </div>
        </div>

        @if($flowMeterInspectionReport->equipment_deficiencies)
            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                        </svg>
                        Festgestellte Mängel
                    </div>
                </div>
                <div class="col">
                    {{ $flowMeterInspectionReport->equipment_deficiencies }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#repeat"></use>
                        </svg>
                        Zweitprüfung erforderlich
                    </div>
                </div>
                <div class="col">
                    {{ $flowMeterInspectionReport->further_inspection_required_string }}
                </div>
            </div>
        @endif

        @if ($flowMeterInspectionReport->comment)
            <div class="text-muted d-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                </svg>
                Sonstige Bemerkungen
            </div>
            <div class="markdown">
                {!! Html::fromMarkdown($flowMeterInspectionReport->comment) !!}
            </div>
        @endif

        @if($flowMeterInspectionReport->appendix())
            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#link"></use>
                        </svg>
                        PDF Anhang
                    </div>
                </div>
                <div class="col">
                    <a href="{{ $flowMeterInspectionReport->appendix()->getUrl() }}">{{ $flowMeterInspectionReport->appendix()->file_name }}</a>
                </div>
            </div>
        @endif

        @if($flowMeterInspectionReport->attachments()->count() > 0)
            <div class="row text-muted d-flex align-items-center mt-1">
                <div class="col">
                    <div class="d-none d-md-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                        </svg>
                        Anhänge
                    </div>
                    <a class="d-inline-flex d-md-none d-inline-flex align-items-center" data-toggle="collapse" href="#collapseFlowMeterInspectionReportAttachments-{{ $flowMeterInspectionReport->id }}" role="button" aria-expanded="false" aria-controls="collapseFlowMeterInspectionReportAttachments-{{ $flowMeterInspectionReport->id }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                        </svg>
                        Anhänge
                    </a>
                </div>
            </div>
            <div class="d-none d-md-block">
                <div class="row">
                    @foreach($flowMeterInspectionReport->attachments() as $attachment)
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
            <div class="collapse d-md-none" id="collapseFlowMeterInspectionReportAttachments-{{ $flowMeterInspectionReport->id }}">
                <div class="row">
                    @foreach($flowMeterInspectionReport->attachments() as $attachment)
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
