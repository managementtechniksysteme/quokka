<div class="overview-card rounded">
    <div class="row align-items-center px-3">
        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ $result->route }}"></a>
            <p class="m-0 ms-100 text-muted text-truncate">
                @include('search.result_icon')
                {{ $result->type }}
            </p>
            <p class="m-0 ms-100 text-truncate">
                {{ $result->name }}
            </p>
        </div>
    </div>
</div>
