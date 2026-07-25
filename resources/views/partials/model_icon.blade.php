@switch($model)
    @case(\App\Models\AdditionsReport::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
        </svg>
        @break
    @case(\App\Models\Address::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use>
        </svg>
        @break
    @case(\App\Models\Company::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#briefcase"></use>
        </svg>
        @break
    @case(\App\Models\ConstructionReport::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
        </svg>
        @break
    @case(\App\Models\DeliveryNote::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#box-seam"></use>
        </svg>
        @break
    @case(\App\Models\Employee::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#person"></use>
        </svg>
        @break
    @case(\App\Models\FinanceGroup::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use>
        </svg>
        @break
    @case(\App\Models\FinanceRecord::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use>
        </svg>
        @break
    @case(\App\Models\FlowMeterInspectionReport::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
        </svg>
        @break
    @case(\App\Models\InspectionReport::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
        </svg>
        @break
    @case(\App\Models\MaterialService::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#cpu"></use>
        </svg>
        @break
    @case(\App\Models\Memo::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#voicemail"></use>
        </svg>
        @break
    @case(\App\Models\Note::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#book"></use>
        </svg>
        @break
    @case(\App\Models\Person::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#person"></use>
        </svg>
        @break
    @case(\App\Models\Project::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use>
        </svg>
        @break
    @case(\App\Models\ServiceReport::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use>
        </svg>
        @break
    @case(\App\Models\Task::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use>
        </svg>
        @break
    @case(\App\Models\TaskComment::class)
        <svg class="icon-bs icon-16 me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#chat-dots"></use>
        </svg>
        @break
    @case(\App\Models\Vehicle::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#truck"></use>
        </svg>
        @break
    @case(\App\Models\WageService::class)
        <svg class="icon-bs icon-baseline me-1">
            <use href="{{ asset('svg/bootstrap-icons.svg') }}#box"></use>
        </svg>
        @break
    @default
        @break
@endswitch
