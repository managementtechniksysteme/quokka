<div class="q-row @if($notification->read_at) q-row--read @endif">
    <a class="stretched-link outline-none" href="{{ $notification->data['route'] }}"></a>

    <span class="q-avatar @if($notification->read_at) q-avatar--muted @endif">
        @include('notification.notification_icon')
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ NotificationHelper::header($notification) }}</div>
        <div class="q-row__desc text-truncate">{{ NotificationHelper::text($notification) }}</div>
        <div class="q-meta">
            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use></svg>
                {{ $notification->created_at->format('d.m.Y H:i') }}
            </span>
        </div>
    </div>

    @unless($notification->read_at)
        <form class="q-row__action" action="{{ route('notifications.destroy', $notification) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="q-kebab" title="Als gelesen markieren">
                <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check"></use></svg>
            </button>
        </form>
    @endunless
</div>
