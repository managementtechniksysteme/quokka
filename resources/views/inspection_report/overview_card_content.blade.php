<div class="overview-card rounded border-status @if($inspectionReport->isNew()) border-primary @elseif($inspectionReport->isSigned()) border-warning @else border-success @endif">
    <div class="mw-100 d-flex flex-grow-1 p-3 align-items-center">

        <div class="mw-100 flex-grow-1 h-100 position-relative">
            <a class="stretched-link outline-none" href="{{ route('inspection-reports.show', $inspectionReport) }}"></a>
            <div class="mw-100 text-truncate">
                Anlage {{ $inspectionReport->equipment_identifier }} vom {{ $inspectionReport->inspected_on }}
            </div>
            <div class="mw-100 text-muted">
                <div class="mw-100 d-inline-flex align-items-center">
                    @switch($inspectionReport->status)
                        @case('new')
                            @if($inspectionReport->signatureRequest)
                                <svg class="icon icon-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                                </svg>
                                {{ $inspectionReport->signatureRequest->created_at }}
                            @else
                                <svg class="icon icon-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                                </svg>
                                {{ $inspectionReport->created_at }}
                            @endif
                            @break
                        @case('signed')
                            <svg class="icon icon-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            {{ $inspectionReport->signature()->created_at }}
                            @break
                        @case('finished')
                            <svg class="icon icon-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            {{ $inspectionReport->updated_at }}
                            @if($inspectionReport->activities->last())
                                ({{ Str::upper($inspectionReport->activities->last()->causer->username) }})
                            @endif
                            @break
                    @endswitch
                    <svg class="icon icon-16 ml-2 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                    </svg>
                    {{ $inspectionReport->employee->person->name }}
                    @unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject')
                        <svg class="icon icon-16 ml-2 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                        </svg>
                        <span class="mw-100 text-truncate">
                            {{ $inspectionReport->project->name }}
                        </span>
                    @endunless
                </div>
            </div>
        </div>

        <div class="d-none d-md-block ml-2">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="inspectionReportOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="inspectionReportOverviewDropdown">
                    @unless($inspectionReport->isFinished())
                        @can('approve', $inspectionReport)
                            <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('inspection-reports.finish', ['inspection_report' => $inspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                                </svg>
                                Erledigen
                            </a>
                        @endcan
                    @endunless
                    @can('update', $inspectionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.edit', $inspectionReport) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('create', \App\Models\InspectionReport::class)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.create', ['template' => $inspectionReport]) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#copy"></use>
                            </svg>
                            Kopieren
                        </a>
                    @endcan
                    @can('email', $inspectionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.email', ['inspection_report' => $inspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $inspectionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.download', $inspectionReport) }}" target="_blank">
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
                    @if(auth()->user()->can('sign', $inspectionReport) || auth()->user()->can('emailSignatureRequest', $inspectionReport) || auth()->user()->can('emailDownloadRequest', $inspectionReport))
                        <div class="dropdown-divider"></div>
                    @endif
                    @can('sign', $inspectionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.sign', ['inspection_report' => $inspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            Unterschreiben lassen
                        </a>
                    @endcan
                    @can('emailSignatureRequest', $inspectionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.email-signature-request', ['inspection_report' => $inspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Unterschrift Anfrage senden
                        </a>
                    @endcan
                    @can('emailDownloadRequest', $inspectionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.email-download-request', ['inspection_report' => $inspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use>
                            </svg>
                            Download Link senden
                        </a>
                    @endcan
                    @if(auth()->user()->can('delete', $inspectionReport) && (auth()->user()->can('sign', $inspectionReport) || auth()->user()->can('emailSignatureRequest', $inspectionReport) || auth()->user()->can('emailDownloadRequest', $inspectionReport)))
                        <div class="dropdown-divider"></div>
                    @endif
                    @can('delete', $inspectionReport)
                        <form action="{{ route('inspection-reports.destroy', ['inspection_report' => $inspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
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
