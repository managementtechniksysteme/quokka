<div class="overview-card rounded border-status @if($constructionReport->isNew()) border-primary @elseif($constructionReport->isSigned()) border-warning @else border-success @endif">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('construction-reports.show', $constructionReport) }}"></a>
            <div>
                @unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject'){{ $constructionReport->project->name }} @endunless#{{ $constructionReport->number }}
                ({{ $constructionReport->services_provided_on }})
            </div>
            <div class="text-muted">
                <div class="d-inline-flex align-items-center">
                    @switch($constructionReport->status)
                        @case('new')
                            @if($constructionReport->signatureRequest)
                                <svg class="icon icon-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                                </svg>
                                {{ $constructionReport->signatureRequest->created_at }}
                            @else
                                <svg class="icon icon-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                                </svg>
                                {{ $constructionReport->created_at }}
                            @endif
                            @break
                        @case('signed')
                            <svg class="icon icon-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            {{ $constructionReport->signature()->created_at }}
                            @break
                        @case('finished')
                            <svg class="icon icon-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            {{ $constructionReport->updated_at }}
                            @break
                    @endswitch
                    <svg class="icon icon-16 ml-2 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                    </svg>
                    <span class="mw-100 text-truncate">
                        {{ $constructionReport->employee->person->name }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="constructionReportOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="constructionReportOverviewDropdown">
                    @unless($constructionReport->isFinished())
                        @can('approve', $constructionReport)
                            <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('construction-reports.finish', ['construction_report' => $constructionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                                </svg>
                                Erledigen
                            </a>
                        @endcan
                    @endunless
                    @can('update', $constructionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.edit', $constructionReport) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('email', $constructionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.email', ['construction_report' => $constructionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $constructionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.download', $constructionReport) }}" target="_blank">
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
                    @if(auth()->user()->can('sign', $constructionReport) || auth()->user()->can('emailSignatureRequest', $constructionReport) || auth()->user()->can('emailDownloadRequest', $constructionReport))
                        <div class="dropdown-divider"></div>
                    @endif
                    @can('sign', $constructionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.sign', ['construction_report' => $constructionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            Unterschreiben lassen
                        </a>
                    @endcan
                    @can('emailSignatureRequest', $constructionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.email-signature-request', ['construction_report' => $constructionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Unterschrift Anfrage senden
                        </a>
                    @endcan
                    @can('emailDownloadRequest', $constructionReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.email-download-request', ['construction_report' => $constructionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use>
                            </svg>
                            Download Link senden
                        </a>
                    @endcan
                    @if(auth()->user()->can('delete', $constructionReport) && (auth()->user()->can('sign', $constructionReport) || auth()->user()->can('emailSignatureRequest', $constructionReport) || auth()->user()->can('emailDownloadRequest', $constructionReport)))
                        <div class="dropdown-divider"></div>
                    @endif
                    @can('delete', $constructionReport)
                        <form action="{{ route('construction-reports.destroy', ['construction_report' => $constructionReport, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
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
