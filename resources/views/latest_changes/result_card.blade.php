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
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                {{ $result->updated_at->format('d.m.Y H:i') }}
            </span>
        </div>
    </div>

    <svg class="icon-bs icon-16 q-row__chevron d-md-none"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-right"></use></svg>
</div>
