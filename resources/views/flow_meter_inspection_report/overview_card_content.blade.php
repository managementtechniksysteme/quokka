<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('flow-meter-inspection-reports.show', $flowMeterInspectionReport) }}"></a>

    <span class="q-avatar">
        <svg class="icon-bs icon-20"><use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">Anlage {{ $flowMeterInspectionReport->equipment_identifier }}, {{ $flowMeterInspectionReport->measuring_point }}</div>
        <div class="q-meta">
            <span class="q-status q-status--{{ $flowMeterInspectionReport->status }}">{{ $flowMeterInspectionReport->status_label }}</span>

            @unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject')
                <span class="q-chip">
                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use></svg>
                    <span class="text-truncate">{{ $flowMeterInspectionReport->project->name }}</span>
                </span>
            @endunless

            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use></svg>
                {{ $flowMeterInspectionReport->inspected_on }}
            </span>

            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use></svg>
                <span class="text-truncate">{{ $flowMeterInspectionReport->employee->person->name }}</span>
            </span>

        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="flowMeterInspectionReportOverviewDropdown-{{ $flowMeterInspectionReport->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="flowMeterInspectionReportOverviewDropdown-{{ $flowMeterInspectionReport->id }}">
            @unless($flowMeterInspectionReport->isFinished())
                @can('approve', $flowMeterInspectionReport)
                    <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.finish', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                        <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use></svg>
                        Erledigen
                    </a>
                @endcan
            @endunless
            @can('update', $flowMeterInspectionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.edit', $flowMeterInspectionReport) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('email', $flowMeterInspectionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.email', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $flowMeterInspectionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.download', $flowMeterInspectionReport) }}" target="_blank">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @if(auth()->user()->can('sign', $flowMeterInspectionReport) || auth()->user()->can('emailSignatureRequest', $flowMeterInspectionReport) || auth()->user()->can('emailDownloadRequest', $flowMeterInspectionReport))
                <div class="dropdown-divider"></div>
            @endif
            @can('sign', $flowMeterInspectionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.sign', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                    Unterschreiben lassen
                </a>
            @endcan
            @can('emailSignatureRequest', $flowMeterInspectionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.email-signature-request', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Unterschrift Anfrage senden
                </a>
            @endcan
            @can('emailDownloadRequest', $flowMeterInspectionReport)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.email-download-request', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use></svg>
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
                        <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use></svg>
                        Entfernen
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>
