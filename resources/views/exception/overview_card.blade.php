<div class="overview-card rounded">
    <div class="row align-items-center px-3">
        <div class="col test=muted flex-grow-1 h-100 py-3">
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

        <div class="col-md-auto d-none d-md-block">
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
