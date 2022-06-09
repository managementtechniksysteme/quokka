<div class="overview-card rounded border-status @if($additionsReport->isNew()) border-primary @elseif($additionsReport->isSigned()) border-warning @else border-success @endif">

        <div class="mw-100 d-flex flex-grow-1 p-3 align-items-center">

            <div class="mw-100 flex-grow-1 h-100 position-relative">
                <a class="stretched-link outline-none" href="{{ route('additions-reports.show', $additionsReport) }}"></a>
                <div class="mw-100 text-truncate">
                    @unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject'){{ $additionsReport->project->name }} @endunless#{{ $additionsReport->number }}
                    ({{ $additionsReport->services_provided_on }})
                </div>
                <div class="text-muted">
                    <div class="d-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                        </svg>
                        {{ Number::toLocal($additionsReport->hours) }}
                        @switch($additionsReport->status)
                            @case('new')
                                @if($additionsReport->signatureRequest)
                                    <svg class="icon icon-16 ml-2 mr-1">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                                    </svg>
                                    {{ $additionsReport->signatureRequest->created_at }}
                                @else
                                    <svg class="icon icon-16 ml-2 mr-1">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                                    </svg>
                                    {{ $additionsReport->created_at }}
                                @endif
                                @break
                            @case('signed')
                                <svg class="icon icon-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                                </svg>
                                {{ $additionsReport->signature()->created_at }}
                                @break
                            @case('finished')
                                <svg class="icon icon-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                                </svg>
                                {{ $additionsReport->updated_at }}
                                @break
                        @endswitch
                        <svg class="icon icon-16 ml-2 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                        </svg>
                        <span class="mw-100 text-truncate">
                            {{ $additionsReport->employee->person->name }}
                        </span>
                    </div>
                </div>
            </div>


            <div class="d-none d-md-block ml-2">
                <div class="dropdown d-inline">
                    <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="additionsReportOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="additionsReportOverviewDropdown">
                        @unless($additionsReport->isFinished())
                            @can('approve', $additionsReport)
                                <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('additions-reports.finish', ['additions_report' => $additionsReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                                    </svg>
                                    Erledigen
                                </a>
                            @endcan
                        @endunless
                        @can('update', $additionsReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('additions-reports.edit', $additionsReport) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                                </svg>
                                Bearbeiten
                            </a>
                        @endcan
                        @can('email', $additionsReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('additions-reports.email', ['additions_report' => $additionsReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                                </svg>
                                Email senden
                            </a>
                        @endcan
                        @can('createPdf', $additionsReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('additions-reports.download', $additionsReport) }}" target="_blank">
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
                        @if(auth()->user()->can('sign', $additionsReport) || auth()->user()->can('emailSignatureRequest', $additionsReport) || auth()->user()->can('emailDownloadRequest', $additionsReport))
                            <div class="dropdown-divider"></div>
                        @endif
                        @can('sign', $additionsReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('additions-reports.sign', ['additions_report' => $additionsReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                                </svg>
                                Unterschreiben lassen
                            </a>
                        @endcan
                        @can('emailSignatureRequest', $additionsReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('additions-reports.email-signature-request', ['additions_report' => $additionsReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                                </svg>
                                Unterschrift Anfrage senden
                            </a>
                        @endcan
                        @can('emailDownloadRequest', $additionsReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('additions-reports.email-download-request', ['additions_report' => $additionsReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use>
                                </svg>
                                Download Link senden
                            </a>
                        @endcan
                        @if(auth()->user()->can('delete', $additionsReport) && (auth()->user()->can('sign', $additionsReport) || auth()->user()->can('emailSignatureRequest', $additionsReport) || auth()->user()->can('emailDownloadRequest', $additionsReport)))
                            <div class="dropdown-divider"></div>
                        @endif
                        @can('delete', $additionsReport)
                            <form action="{{ route('additions-reports.destroy', ['additions_report' => $additionsReport, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
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
