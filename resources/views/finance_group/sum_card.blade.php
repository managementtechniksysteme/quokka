<div class="overview-card rounded @if($sum >= 0) bg-green-100 @else bg-red-100 @endif ">
    <div class="mw-100 d-flex flex-grow-1 p-3 align-items-center">
        <div class="@if($sum >= 0) text-green-700 @else text-red-700 @endif mw-100 d-flex flex-grow-1 h-100 align-items-center">
            <strong>Summe</strong>
        </div>

        <div class="d-none d-sm-block ms-2">
            <span class="@if($sum >= 0) text-green-700 @else text-red-700 @endif d-inline-flex align-items-center">
                <svg class="icon-bs icon-16 me-1">
                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use>
                </svg>
                <strong>{{ Number::toLocal($sum, 2) }}</strong>
            </span>
        </div>

    </div>
</div>
