<div class="overview-card rounded">
    <div class="d-flex flex-grow-1 p-3 align-items-center">
        <div class="d-flex flex-column flex-grow-1 h-100 position-relative">
            <a class="stretched-link outline-none" href="{{ $notification->data['route'] }}"></a>
            <div class="text-muted">
                @include('notification.notification_icon')
                {{ NotificationHelper::header($notification) }}
            </div>
            <div class="flex-wrap @if($notification->read_at) text-muted @endif">
                {{ NotificationHelper::text($notification) }}
            </div>
        </div>

        <div class="d-none d-lg-flex flex-shrink-0 text-muted align-items-center mr-4">
            <svg class="icon icon-16 mr-1">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
            </svg>
            {{ $notification->created_at->format('d.m.Y H:i') }}
        </div>

        @if( !$notification->read_at )
            <div class="d-none d-lg-block">
                <form action="{{ route('notifications.destroy', $notification) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-outline-success d-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                        </svg>
                        Gelesen
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>
