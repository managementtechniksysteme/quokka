<div class="row">
    <div class="col-sm-2">
        <div class="text-muted d-flex align-items-center">
            <svg class="icon-bs icon-16 me-2">
                <use href="{{ asset('svg/bootstrap-icons.svg') }}#chat-dots"></use>
            </svg>
            Beschreibung
        </div>
    </div>
    <div class="col">
        {{ $service->description }}
    </div>
</div>
