{{--
    Single-value horizontal bar row.
    Expects: $label, $value (display string), $percentage (0-100), $color (css color or null for muted), $icon (bootstrap icon name or null)
--}}
<div class="q-hbar-row">
    <div class="q-hbar-row__top">
        <span class="q-hbar-row__label">
            @isset($icon)
                <svg class="icon-bs icon-16" style="color:var(--q-faint); margin-right:.35rem"><use href="{{ asset('svg/bootstrap-icons.svg') }}#{{ $icon }}"></use></svg>
            @endisset
            {{ $label }}
        </span>
        <span class="q-hbar-row__value" @isset($color) style="color:{{ $color }}" @endisset>{{ $value }}</span>
    </div>
    <div class="q-hbar-track">
        <div class="q-hbar-fill @empty($color ?? null) q-hbar-fill--muted @endempty" style="width:{{ $percentage }}%; @isset($color) background:{{ $color }} @endisset"></div>
    </div>
</div>
