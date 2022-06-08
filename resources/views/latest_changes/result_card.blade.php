<div class="overview-card rounded">
    <div class="row align-items-center px-3 position-relative">
        <a class="stretched-link outline-none" href="{{ $result->route }}"></a>
        <div class="col flex-grow-1 h-100 py-3">
            <p class="m-0 mw-100 text-muted text-truncate">
                @component('partials.model_icon', ['model' => $result->model])
                @endcomponent
                {{ $result->type }}
            </p>
            <p class="m-0 ms-100 text-truncate">
                {{ $result->name }}
            </p>
        </div>

        <div class="d-none d-md-flex col-sm-auto text-muted text-right">
            <svg class="icon icon-16 mr-1">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
            </svg>
            {{ $result->updated_at->format('d.m.Y H:i') }}
        </div>
    </div>
</div>
