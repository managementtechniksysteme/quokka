<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('inspection-reports.show', $inspectionReport) }}"></a>

    <span class="q-avatar">
        <svg class="icon-bs icon-20"><use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">Anlage {{ $inspectionReport->equipment_identifier }}</div>
        <div class="q-meta">
            <span class="q-status q-status--{{ $inspectionReport->status }}">{{ $inspectionReport->status_label }}</span>

            @unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject')
                <span class="q-chip">
                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use></svg>
                    <span class="text-truncate">{{ $inspectionReport->project->name }}</span>
                </span>
            @endunless

            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use></svg>
                {{ $inspectionReport->inspected_on }}
            </span>

            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use></svg>
                <span class="text-truncate">{{ $inspectionReport->employee->person->name }}</span>
            </span>

        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="inspectionReportOverviewDropdown-{{ $inspectionReport->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="inspectionReportOverviewDropdown-{{ $inspectionReport->id }}">
            @unless($inspectionReport->isFinished())
                @can('approve', $inspectionReport)
                    <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('inspection-reports.finish', ['inspection_report' => $inspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                        <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use></svg>
                        Erledigen
                    </a>
                @endcan
            @endunless
            @can('update', $inspectionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.edit', $inspectionReport) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('email', $inspectionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.email', ['inspection_report' => $inspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $inspectionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.download', $inspectionReport) }}" target="_blank">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @if(auth()->user()->can('sign', $inspectionReport) || auth()->user()->can('emailSignatureRequest', $inspectionReport) || auth()->user()->can('emailDownloadRequest', $inspectionReport))
                <div class="dropdown-divider"></div>
            @endif
            @can('sign', $inspectionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.sign', ['inspection_report' => $inspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                    Unterschreiben lassen
                </a>
            @endcan
            @can('emailSignatureRequest', $inspectionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.email-signature-request', ['inspection_report' => $inspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Unterschrift Anfrage senden
                </a>
            @endcan
            @can('emailDownloadRequest', $inspectionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('inspection-reports.email-download-request', ['inspection_report' => $inspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use></svg>
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
                        <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use></svg>
                        Entfernen
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>
