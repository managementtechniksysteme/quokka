<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('construction-reports.show', $constructionReport) }}"></a>

    <span class="q-avatar">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title q-row__title--numbered">
            @unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject')
                <span class="q-row__title-main text-truncate">{{ $constructionReport->project->name }}</span>
                <span class="q-row__sub q-mono flex-shrink-0">#{{ $constructionReport->number }}</span>
            @else
                <span class="q-mono">#{{ $constructionReport->number }}</span>
            @endunless
        </div>

        {{-- Desktop: status + date + technician, unchanged. --}}
        <div class="q-meta d-none d-md-flex">
            <span class="q-status q-status--{{ $constructionReport->status }}">{{ $constructionReport->status_label }}</span>

            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                {{ $constructionReport->services_provided_on }}
            </span>

            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#person"></use></svg>
                <span class="text-truncate">{{ $constructionReport->employee->person->name }}</span>
            </span>
        </div>

        {{-- Mobile: technician/"who" chip drops. --}}
        <div class="q-meta d-md-none">
            <span class="q-status q-status--{{ $constructionReport->status }}">{{ $constructionReport->status_label }}</span>

            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                {{ $constructionReport->services_provided_on }}
            </span>
        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="constructionReportOverviewDropdown-{{ $constructionReport->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="constructionReportOverviewDropdown-{{ $constructionReport->id }}">
            @unless($constructionReport->isFinished())
                @can('approve', $constructionReport)
                    <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('construction-reports.finish', ['construction_report' => $constructionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                        <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                        Erledigen
                    </a>
                @endcan
            @endunless
            @can('update', $constructionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.edit', $constructionReport) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('email', $constructionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.email', ['construction_report' => $constructionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $constructionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.download', $constructionReport) }}" target="_blank">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @if(auth()->user()->can('sign', $constructionReport) || auth()->user()->can('emailSignatureRequest', $constructionReport) || auth()->user()->can('emailDownloadRequest', $constructionReport))
                <div class="dropdown-divider"></div>
            @endif
            @can('sign', $constructionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.sign', ['construction_report' => $constructionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                    Unterschreiben lassen
                </a>
            @endcan
            @can('emailSignatureRequest', $constructionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.email-signature-request', ['construction_report' => $constructionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                    Unterschrift Anfrage senden
                </a>
            @endcan
            @can('emailDownloadRequest', $constructionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('construction-reports.email-download-request', ['construction_report' => $constructionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#download"></use></svg>
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
                        <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                        Entfernen
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <svg class="icon-bs icon-16 q-row__chevron d-md-none"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-right"></use></svg>
</div>
