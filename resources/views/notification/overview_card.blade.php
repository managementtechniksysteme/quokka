<div class="overview-card rounded">
    <div class="mw-100 d-flex flex-grow-1 p-3 align-items-center">
        <div class="mw-100 flex-grow-1 h-100 position-relative">
            <a class="stretched-link outline-none" href="{{ $notification->data['route'] }}"></a>
            <div class="text-muted">
                @include('notification.notification_icon')
                {{ NotificationHelper::header($notification) }}
            </div>
            <div class="mw-100 text-truncate">
                {{ NotificationHelper::text($notification) }}
            </div>
        </div>

        <div class="d-none d-md-flex text-muted align-items-center mr-4">
            <svg class="icon icon-16 mr-1">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
            </svg>
            {{ $notification->created_at->format('d.m.Y H:i') }}
        </div>

        <div class="d-none d-md-block">
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

    </div>
</div>
