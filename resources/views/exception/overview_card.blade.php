<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('exceptions.show', $exception['uuid']) }}"></a>

    <span class="q-avatar">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate q-mono">{{ $exception['uuid'] }}</div>
        <div class="q-meta">
            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                {{ $exception['created_at']->format('d.m.Y H:i:s') }}
            </span>
        </div>
    </div>

    {{-- Desktop only: exceptions.show already has its own delete button, same
         as every other model's detail page — mobile defers to it via the
         chevron rather than duplicating the action here (2026-07-21). --}}
    @can('tools-deleteexceptions')
        <form class="q-row__action d-none d-md-block" action="{{ route('exceptions.destroy', $exception['uuid']) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="q-kebab q-kebab--danger" title="Entfernen">
                <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
            </button>
        </form>
    @endcan

    <svg class="icon-bs icon-16 q-row__chevron d-md-none"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-right"></use></svg>
</div>
