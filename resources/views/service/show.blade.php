<div class="row">
    <div class="col-sm-2">
        <div class="text-muted d-flex align-items-center">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Beschreibung
        </div>
    </div>
    <div class="col">
        {{ $service->description }}
    </div>
</div>
