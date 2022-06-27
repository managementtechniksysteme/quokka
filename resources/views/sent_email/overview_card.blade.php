<div class="overview-card rounded">
    <div class="d-flex flex-grow-1 p-3 align-items-center">
        <div class="d-flex flex-column flex-grow-1 h-100">
            <div class="text-muted">
                <svg class="icon icon-baseline mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                </svg>
                {{ $activity->properties['subject'] }}
            </div>
            @if(!empty($activity->properties['to']))
                <div class="flex-wrap">
                    <span class="text-muted">An:</span>
                    {{  implode(', ', $activity->properties['to']) }}
                </div>
            @endif
            @if(!empty($activity->properties['cc']))
                <div class="flex-wrap">
                    <span class="text-muted">CC:</span>
                    {{  implode(', ', $activity->properties['cc']) }}
                </div>
            @endif
            @if(!empty($activity->properties['bcc']))
                <div class="flex-wrap">
                    <span class="text-muted">BCC:</span>
                    {{  implode(', ', $activity->properties['bcc']) }}
                </div>
            @endif
        </div>

        <div class="d-none d-lg-flex flex-shrink-0 align-items-center mr-4 text-muted">
            <svg class="icon icon-16 mr-1">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
            </svg>
            {{ $activity->created_at->format('d.m.Y H:i') }}
        </div>

    </div>
</div>
