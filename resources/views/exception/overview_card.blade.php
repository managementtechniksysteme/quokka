<div class="overview-card rounded">
    <div class="mw-100 d-fex flex-grow-1 p-3 align-items-center">
        <div class="mw-100 flex-grow-1 h-100 position-relative">
            <a class="stretched-link outline-none" href="{{ route('exceptions.show', $exception['uuid']) }}"></a>
            <div class="mw-100 text-truncate">
                {{ $exception['uuid'] }}
            </div>
            <div class="text-muted d-inline-flex align-items-center">
                <svg class="icon icon-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                </svg>
                {{ $exception['created_at']->format('d.m.Y H:i:s') }}
            </div>
        </div>

        <div class="d-none d-md-block ml-2">
            @can('tools-deleteexceptions')
                <form action="{{ route('exceptions.destroy', $exception['uuid']) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                        </svg>
                        Entfernen
                    </button>
                </form>
            @endcan
        </div>

    </div>
</div>
