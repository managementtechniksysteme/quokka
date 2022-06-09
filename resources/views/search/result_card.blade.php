<div class="overview-card rounded">
    <div class="d-flex mw-100 p-3 align-items-center">
        <div class="flex-grow-1 mw-100 h-100 position-relative">
            <a class="stretched-link outline-none" href="{{ $result->route }}"></a>
            <p class="m-0 mw-100 text-muted text-truncate">
                @component('partials.model_icon', ['model' => $result->model])
                @endcomponent
                {{ $result->type }}
            </p>
            <p class="m-0 mw-100 text-truncate">
                {{ $result->name }}
            </p>
        </div>
    </div>
</div>
