<div class="overview-card rounded border-status @if($flowMeterInspectionReport->isNew()) border-primary @elseif($flowMeterInspectionReport->isSigned()) border-warning @else border-success @endif">
    <div class="mw-100 d-flex flex-grow-1 p-3 align-items-center">

        <div class="mw-100 flex-grow-1 h-100 position-relative">
            <a class="stretched-link outline-none" href="{{ route('flow-meter-inspection-reports.show', $flowMeterInspectionReport) }}"></a>
            <div class="mw-100 text-truncate">
                Anlage {{ $flowMeterInspectionReport->equipment_identifier }}, {{ $flowMeterInspectionReport->measuring_point }}  vom {{ $flowMeterInspectionReport->inspected_on }}
            </div>
            <div class="mw-100 text-muted">
                <div class="mw-100 d-inline-flex align-items-center">
                    @switch($flowMeterInspectionReport->status)
                        @case('new')
                            @if($flowMeterInspectionReport->signatureRequest)
                                <svg class="icon icon-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                                </svg>
                                {{ $flowMeterInspectionReport->signatureRequest->created_at }}
                            @else
                                <svg class="icon icon-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                                </svg>
                                {{ $flowMeterInspectionReport->created_at }}
                            @endif
                            @break
                        @case('signed')
                            <svg class="icon icon-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            {{ $flowMeterInspectionReport->signature()->created_at }}
                            @break
                        @case('finished')
                            <svg class="icon icon-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            {{ $flowMeterInspectionReport->updated_at }}
                            @if($flowMeterInspectionReport->activities->last())
                                ({{ Str::upper($flowMeterInspectionReport->activities->last()->causer->username) }})
                            @endif
                            @break
                    @endswitch
                    <svg class="icon icon-16 ml-2 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                    </svg>
                    {{ $flowMeterInspectionReport->employee->person->name }}
                    @unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject')
                        <svg class="icon icon-16 ml-2 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                        </svg>
                        <span class="mw-100 text-truncate">
                            {{ $flowMeterInspectionReport->project->name }}
                        </span>
                    @endunless
                </div>
            </div>
        </div>

        <div class="d-none d-md-block ml-2">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="flowMeterInspectionReportOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="flowMeterInspectionReportOverviewDropdown">
                    @unless($flowMeterInspectionReport->isFinished())
                        @can('approve', $flowMeterInspectionReport)
                            <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.finish', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                                </svg>
                                Erledigen
                            </a>
                        @endcan
                    @endunless
                    @can('update', $flowMeterInspectionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.edit', $flowMeterInspectionReport) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('create', \App\Models\FlowMeterInspectionReport::class)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.create', ['template' => $flowMeterInspectionReport]) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#copy"></use>
                            </svg>
                            Kopieren
                        </a>
                    @endcan
                    @can('email', $flowMeterInspectionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.email', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $flowMeterInspectionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.download', $flowMeterInspectionReport) }}" target="_blank">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                            </svg>
                            PDF erstellen
                        </a>
                    @endcan
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                        Favorisieren
                    </a>
                    @if(auth()->user()->can('sign', $flowMeterInspectionReport) || auth()->user()->can('emailSignatureRequest', $flowMeterInspectionReport) || auth()->user()->can('emailDownloadRequest', $flowMeterInspectionReport))
                        <div class="dropdown-divider"></div>
                    @endif
                    @can('sign', $flowMeterInspectionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.sign', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            Unterschreiben lassen
                        </a>
                    @endcan
                    @can('emailSignatureRequest', $flowMeterInspectionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.email-signature-request', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Unterschrift Anfrage senden
                        </a>
                    @endcan
                    @can('emailDownloadRequest', $flowMeterInspectionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.email-download-request', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use>
                            </svg>
                            Download Link senden
                        </a>
                    @endcan
                    @if(auth()->user()->can('delete', $flowMeterInspectionReport) && (auth()->user()->can('sign', $flowMeterInspectionReport) || auth()->user()->can('emailSignatureRequest', $flowMeterInspectionReport) || auth()->user()->can('emailDownloadRequest', $flowMeterInspectionReport)))
                        <div class="dropdown-divider"></div>
                    @endif
                    @can('delete', $flowMeterInspectionReport)
                        <form action="{{ route('flow-meter-inspection-reports.destroy', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
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

    </div>
</div>
