{{-- Accounting breakdown donut. Expects: $items (Collection of {label, value}). --}}
@php
    $donut = \App\Support\Metrics\DonutChart::segments($items, 'value');
    $segments = $donut['segments'];
    $total = $donut['total'];
@endphp
<div class="q-donut-wrap">
    <svg width="150" height="150" viewBox="0 0 150 150">
        <circle cx="75" cy="75" r="58" fill="none" stroke="var(--q-border-2)" stroke-width="20"></circle>
        @foreach($segments as $segment)
            <circle cx="75" cy="75" r="58" fill="none" stroke="var({{ $segment->color }})" stroke-width="20"
                    stroke-dasharray="{{ $segment->dasharray }}" stroke-dashoffset="{{ $segment->dashoffset }}" transform="rotate(-90 75 75)"></circle>
        @endforeach
        <text x="75" y="72" text-anchor="middle" class="q-donut-center-value">{{ number_format($total, 0, ',', '.') }}&nbsp;€</text>
        <text x="75" y="88" text-anchor="middle" class="q-donut-center-label">GESAMT</text>
    </svg>
    <div class="q-donut-legend">
        @forelse($segments as $segment)
            <div class="q-donut-legend__row">
                <span class="q-chart-legend__dot" style="background:var({{ $segment->color }})"></span>
                <span class="q-donut-legend__label">{{ $segment->label }}</span>
                <span class="q-donut-legend__value">{{ number_format($segment->value, 0, ',', '.') }}&nbsp;€</span>
                <span class="q-donut-legend__pct">{{ $segment->percentage }}%</span>
            </div>
        @empty
            <p class="text-muted mb-0">Keine Daten im Zeitraum.</p>
        @endforelse
    </div>
</div>
