@switch($notification->type)
    @case(\App\Notifications\AdditionsReportInvolvedNotification::class)
        <svg class="icon-bs icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
        </svg>
        @break
    @case(\App\Notifications\AdditionsReportMentionNotification::class)
        <svg class="icon-bs icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
        </svg>
        @break
    @case(\App\Notifications\AdditionsReportSignedNotification::class)
        <svg class="icon-bs icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
        </svg>
        @break
    @case(\App\Notifications\ApplicationVersionUpdateNotification::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
        </svg>
        @break
    @case(\App\Notifications\CommentInvolvedNotification::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
        </svg>
        @break
    @case(\App\Notifications\CommentMentionNotification::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
        </svg>
        @break
    @case(\App\Notifications\ConstructionReportInvolvedNotification::class)
        <svg class="icon-bs icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
        </svg>
        @break
    @case(\App\Notifications\ConstructionReportMentionNotification::class)
        <svg class="icon-bs icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
        </svg>
        @break
    @case(\App\Notifications\ConstructionReportSignedNotification::class)
        <svg class="icon-bs icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
        </svg>
        @break
    @case(\App\Notifications\HolidayAllowanceAdjustmentNotification::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>
        </svg>
        @break
    @case(\App\Notifications\InspectionReportMentionNotification::class)
        <svg class="icon-bs icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
        </svg>
        @break
    @case(\App\Notifications\InspectionReportSignedNotification::class)
        <svg class="icon-bs icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
        </svg>
        @break
    @case(\App\Notifications\MemoInvolvedNotification::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
        </svg>
        @break
    @case(\App\Notifications\MemoMentionNotification::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
        </svg>
        @break
    @case(\App\Notifications\ServiceReportMentionNotification::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
        </svg>
        @break
    @case(\App\Notifications\ServiceReportSignedNotification::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
        </svg>
        @break
    @case(\App\Notifications\TaskInvolvedNotification::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
        </svg>
        @break
    @case(\App\Notifications\TaskMentionNotification::class)
        <svg class="icon icon-baseline mr-1">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
        </svg>
        @break
@endswitch
