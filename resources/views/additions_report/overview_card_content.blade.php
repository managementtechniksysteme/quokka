<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('additions-reports.show', $additionsReport) }}"></a>

    <span class="q-avatar">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">@unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject'){{ $additionsReport->project->name }} <span class="q-row__sub q-mono">#{{ $additionsReport->number }}</span>@else<span class="q-mono">#{{ $additionsReport->number }}</span>@endunless</div>
        <div class="q-meta">
            <span class="q-status q-status--{{ $additionsReport->status }}">{{ $additionsReport->status_label }}</span>

            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                {{ $additionsReport->services_provided_on }}
            </span>

            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#person"></use></svg>
                <span class="text-truncate">{{ $additionsReport->employee->person->name }}</span>
            </span>

            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clock"></use></svg>
                {{ Number::toLocal($additionsReport->hours) }}
            </span>

        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="additionsReportOverviewDropdown-{{ $additionsReport->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="additionsReportOverviewDropdown-{{ $additionsReport->id }}">
            @unless($additionsReport->isFinished())
                @can('approve', $additionsReport)
                    <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('additions-reports.finish', ['additions_report' => $additionsReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                        <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                        Erledigen
                    </a>
                @endcan
            @endunless
            @can('update', $additionsReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('additions-reports.edit', $additionsReport) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('email', $additionsReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('additions-reports.email', ['additions_report' => $additionsReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $additionsReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('additions-reports.download', $additionsReport) }}" target="_blank">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @if(auth()->user()->can('sign', $additionsReport) || auth()->user()->can('emailSignatureRequest', $additionsReport) || auth()->user()->can('emailDownloadRequest', $additionsReport))
                <div class="dropdown-divider"></div>
            @endif
            @can('sign', $additionsReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('additions-reports.sign', ['additions_report' => $additionsReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                    Unterschreiben lassen
                </a>
            @endcan
            @can('emailSignatureRequest', $additionsReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('additions-reports.email-signature-request', ['additions_report' => $additionsReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                    Unterschrift Anfrage senden
                </a>
            @endcan
            @can('emailDownloadRequest', $additionsReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('additions-reports.email-download-request', ['additions_report' => $additionsReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#download"></use></svg>
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
                        <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                        Entfernen
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>
