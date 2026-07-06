<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('service-reports.show', $serviceReport) }}"></a>

    <span class="q-avatar">
        <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">@unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject'){{ $serviceReport->project->name }} <span class="q-row__sub q-mono">#{{ $serviceReport->number }}</span>@else<span class="q-mono">#{{ $serviceReport->number }}</span>@endunless</div>
        <div class="q-meta">
            <span class="q-status q-status--{{ $serviceReport->status }}">{{ $serviceReport->status_label }}</span>

            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use></svg>
                {{ \Carbon\Carbon::parse($serviceReport->services_min_provided_on)->format('d.m.Y') }}@if(\Carbon\Carbon::parse($serviceReport->services_min_provided_on)->ne(\Carbon\Carbon::parse($serviceReport->services_max_provided_on)))
                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use></svg>
                    {{ \Carbon\Carbon::parse($serviceReport->services_max_provided_on)->format('d.m.Y') }}@endif
            </span>

            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use></svg>
                <span class="text-truncate">{{ $serviceReport->employee->person->name }}</span>
            </span>

            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use></svg>
                {{ Number::toLocal($serviceReport->services_sum_hours) }}
            </span>

            @if($serviceReport->services_sum_kilometres > 0)
                <span class="q-chip">
                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#truck"></use></svg>
                    {{ Number::toLocal($serviceReport->services_sum_kilometres) }}
                </span>
            @endif

        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="serviceReportOverviewDropdown-{{ $serviceReport->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="serviceReportOverviewDropdown-{{ $serviceReport->id }}">
            @unless($serviceReport->isFinished())
                @can('approve', $serviceReport)
                    <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('service-reports.finish', ['service_report' => $serviceReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                        <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use></svg>
                        Erledigen
                    </a>
                @endcan
            @endunless
            @can('update', $serviceReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.edit', $serviceReport) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('email', $serviceReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.email', ['service_report' => $serviceReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $serviceReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.download', $serviceReport) }}" target="_blank">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @if(auth()->user()->can('sign', $serviceReport) || auth()->user()->can('emailSignatureRequest', $serviceReport) || auth()->user()->can('emailDownloadRequest', $serviceReport))
                <div class="dropdown-divider"></div>
            @endif
            @can('sign', $serviceReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.sign', ['service_report' => $serviceReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                    Unterschreiben lassen
                </a>
            @endcan
            @can('emailSignatureRequest', $serviceReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.email-signature-request', ['service_report' => $serviceReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Unterschrift Anfrage senden
                </a>
            @endcan
            @can('emailDownloadRequest', $serviceReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.email-download-request', ['service_report' => $serviceReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use></svg>
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
                        <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use></svg>
                        Entfernen
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>
