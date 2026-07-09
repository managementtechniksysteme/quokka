<div class="q-row position-relative">
    <a class="stretched-link" href="{{ route('finance-records.show', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}"></a>

    <div class="q-avatar q-avatar--icon">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
    </div>

    <div class="q-row__main">
        <div class="q-row__title">{{ $financeRecord->title }}</div>
        <div class="q-meta">
            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                {{ $financeRecord->billed_on->format('d.m.Y') }}
            </span>
        </div>
    </div>

    <div class="q-metric @if($financeRecord->amount < 0) q-metric--danger @endif">
        <div class="q-metric__value q-mono">{{ Number::toLocal($financeRecord->amount, 2) }}</div>
        <div class="q-metric__label">Betrag</div>
    </div>

    <div class="dropdown position-relative">
        <button class="q-kebab" type="button" id="financeRecordDropdown{{ $financeRecord->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="financeRecordDropdown{{ $financeRecord->id }}">
            @can('update', $financeRecord)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('finance-records.edit', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('delete', $financeRecord)
                <form action="{{ route('finance-records.destroy', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}" method="post">
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
