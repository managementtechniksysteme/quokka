<div class="overview-card rounded border-status @if($serviceReport->isNew()) border-primary @elseif($serviceReport->isSigned()) border-warning @else border-success @endif">
    <div class="mw-100 d-flex flex-grow-1 p-3 align-items-center">

        <div class="mw-100 flex-grow-1 h-100 position-relative">
            <a class="stretched-link outline-none" href="{{ route('service-reports.show', $serviceReport) }}"></a>
            <div class="mw-100 text-truncate">
                @unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject'){{ $serviceReport->project->name }} @endunless#{{ $serviceReport->number }}
                    ({{ \Carbon\Carbon::parse($serviceReport->services_min_provided_on) }}@if(\Carbon\Carbon::parse($serviceReport->services_min_provided_on)->ne(\Carbon\Carbon::parse($serviceReport->services_max_provided_on)))
                        <svg class="icon icon-16 mx-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                        </svg>
                        {{ \Carbon\Carbon::parse($serviceReport->services_max_provided_on) }}@endif)
            </div>
            <div class="mw-100 text-muted">
                <div class="mw-100 d-inline-flex align-items-center">
                    <svg class="icon icon-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                    </svg>
                    {{ Number::toLocal($serviceReport->services_sum_hours) }}
                    <svg class="icon icon-16 ml-2 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#truck"></use>
                    </svg>
                    {{ Number::toLocal($serviceReport->services_sum_kilometres) }}
                    @switch($serviceReport->status)
                        @case('new')
                            @if($serviceReport->signatureRequest)
                                <svg class="icon icon-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                                </svg>
                                {{ $serviceReport->signatureRequest->created_at }}
                            @else
                                <svg class="icon icon-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                                </svg>
                                {{ $serviceReport->created_at }}
                            @endif
                            @break
                        @case('signed')
                            <svg class="icon icon-16 ml-2 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            {{ $serviceReport->signature()->created_at }}
                        @break
                        @case('finished')
                            <svg class="icon icon-16 ml-2 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            {{ $serviceReport->updated_at }}
                        @break
                    @endswitch
                    <svg class="icon icon-16 ml-2 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                    </svg>
                    <span class="mw-100 text-truncate">
                        {{ $serviceReport->employee->person->name }}
                    </span>
                </div>
            </div>
        </div>

        <div class="d-none d-md-block ml-2">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="serviceReportOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="serviceReportOverviewDropdown">
                    @unless($serviceReport->isFinished())
                        @can('approve', $serviceReport)
                            <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('service-reports.finish', ['service_report' => $serviceReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                                </svg>
                                Erledigen
                            </a>
                        @endcan
                    @endunless
                    @can('update', $serviceReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.edit', $serviceReport) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('email', $serviceReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.email', ['service_report' => $serviceReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $serviceReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.download', $serviceReport) }}" target="_blank">
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
                    @if(auth()->user()->can('sign', $serviceReport) || auth()->user()->can('emailSignatureRequest', $serviceReport) || auth()->user()->can('emailDownloadRequest', $serviceReport))
                        <div class="dropdown-divider"></div>
                    @endif
                    @can('sign', $serviceReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.sign', ['service_report' => $serviceReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            Unterschreiben lassen
                        </a>
                    @endcan
                    @can('emailSignatureRequest', $serviceReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.email-signature-request', ['service_report' => $serviceReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Unterschrift Anfrage senden
                        </a>
                    @endcan
                    @can('emailDownloadRequest', $serviceReport)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.email-download-request', ['service_report' => $serviceReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use>
                            </svg>
                            Download Link senden
                        </a>
                    @endcan
                    @if(auth()->user()->can('delete', $serviceReport) && (auth()->user()->can('sign', $serviceReport) || auth()->user()->can('emailSignatureRequest', $serviceReport) || auth()->user()->can('emailDownloadRequest', $serviceReport)))
                        <div class="dropdown-divider"></div>
                    @endif
                    @can('delete', $serviceReport)
                        <form action="{{ route('service-reports.destroy', ['service_report' => $serviceReport, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
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
