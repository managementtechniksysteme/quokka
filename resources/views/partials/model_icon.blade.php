@switch($model)
    @case(\App\Models\AdditionsReport::class)
        <svg class="icon-bs icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
        </svg>
        @break
    @case(\App\Models\Address::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
        </svg>
        @break
    @case(\App\Models\Company::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
        </svg>
        @break
    @case(\App\Models\ConstructionReport::class)
        <svg class="icon-bs icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
        </svg>
        @break
    @case(\App\Models\Employee::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
        </svg>
        @break
    @case(\App\Models\FlowMeterInspectionReport::class)
        <svg class="icon-bs icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
        </svg>
        @break
    @case(\App\Models\InspectionReport::class)
        <svg class="icon-bs icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
        </svg>
        @break
    @case(\App\Models\MaterialService::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cpu"></use>
        </svg>
        @break
    @case(\App\Models\Memo::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
        </svg>
        @break
    @case(\App\Models\Note::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#book-open"></use>
        </svg>
        @break
    @case(\App\Models\Person::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
        </svg>
        @break
    @case(\App\Models\Project::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
        </svg>
        @break
    @case(\App\Models\ServiceReport::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
        </svg>
        @break
    @case(\App\Models\Task::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
        </svg>
        @break
    @case(\App\Models\TaskComment::class)
        <svg class="icon icon-16 mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
        </svg>
        @break
    @case(\App\Models\Vehicle::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#truck"></use>
        </svg>
        @break
    @case(\App\Models\WageService::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#box"></use>
        </svg>
        @break
    @default
        @break
@endswitch
