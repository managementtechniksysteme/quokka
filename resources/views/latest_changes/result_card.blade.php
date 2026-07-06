<div class="q-row">
    <a class="stretched-link outline-none" href="{{ $result->route }}"></a>

    <span class="q-avatar q-avatar--icon">
        @include('partials.model_icon', ['model' => $result->model])
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $result->name }}</div>
        <div class="q-meta">
            <span class="q-chip">
                @include('partials.model_icon', ['model' => $result->model])
                {{ $result->type }}
            </span>
            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use></svg>
                {{ $result->updated_at->format('d.m.Y H:i') }}
            </span>
        </div>
    </div>
</div>
