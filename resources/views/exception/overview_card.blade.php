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

    @can('tools-deleteexceptions')
        {{-- single delete action: a direct icon button (no dropdown for one item),
             lifted above the row's stretched-link --}}
        <form class="q-row__action" action="{{ route('exceptions.destroy', $exception['uuid']) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="q-kebab q-kebab--danger" title="Entfernen">
                <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
            </button>
        </form>
    @endcan
</div>
