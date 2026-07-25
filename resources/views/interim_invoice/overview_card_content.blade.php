<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('interim-invoices.show', ['project' => $interimInvoice->project, 'interim_invoice' => $interimInvoice]) }}"></a>

    <span class="q-avatar">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#file-text"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $interimInvoice->title }}</div>
        <div class="q-meta">
            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                {{ $interimInvoice->billed_on }}
            </span>
            {{-- Mobile: .q-metric (desktop's hero amount) is dropped entirely
                 by the shared list-row mobile rule — repeated here as a chip
                 instead, same fix as finance_record's identical pattern
                 (2026-07-22, user report). --}}
            <span class="q-chip d-md-none q-mono">
                {{ Number::toLocal($interimInvoice->amount, 2) }}
            </span>
        </div>
    </div>

    <div class="q-metric">
        <div class="q-metric__value">{{ Number::toLocal($interimInvoice->amount, 2) }}</div>
        <div class="q-metric__label">Betrag</div>
    </div>

    <div class="dropdown">
        <button class="q-kebab" type="button" id="interimInvoiceOverviewDropdown-{{ $interimInvoice->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="interimInvoiceOverviewDropdown-{{ $interimInvoice->id }}">
            @can('update', $interimInvoice)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('interim-invoices.edit', ['project' => $interimInvoice->project, 'interim_invoice' => $interimInvoice]) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('delete', $interimInvoice)
                <form action="{{ route('interim-invoices.destroy', ['project' => $interimInvoice->project, 'interim_invoice' => $interimInvoice]) }}" method="post">
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
