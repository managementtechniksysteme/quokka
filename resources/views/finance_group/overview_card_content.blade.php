<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('finance-groups.show', $financeGroup) }}"></a>

    <span class="q-avatar">
        <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#layers"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $financeGroup->title }}</div>
        <div class="q-meta">
            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#list"></use></svg>
                {{ $financeGroup->finance_records_count }} Einträge
            </span>
        </div>
    </div>

    @if($financeGroup->finance_records_count)
        <div class="q-metric @if($financeGroup->finance_records_sum_amount < 0) q-metric--danger @endif">
            <div class="q-metric__value q-mono">{{ Number::toLocal($financeGroup->finance_records_sum_amount, 2) }}</div>
            <div class="q-metric__label">Betrag</div>
        </div>
    @endif

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="financeGroupOverviewDropdown-{{ $financeGroup->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="financeGroupOverviewDropdown-{{ $financeGroup->id }}">
            @can('update', $financeGroup)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('finance-groups.edit', $financeGroup) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('delete', $financeGroup)
                <form action="{{ route('finance-groups.destroy', $financeGroup) }}" method="post">
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
