{{--
    Mean/median dual thin-bar row, scaled against $maxMean across the list.
    Expects: $label, $mean, $median, $maxMean, $unit (e.g. "Tage")
--}}
@php
    $meanPercentage = $maxMean > 0 ? min(100, round($mean / $maxMean * 100, 1)) : 0;
    $medianPercentage = $maxMean > 0 ? min(100, round($median / $maxMean * 100, 1)) : 0;
@endphp
<div class="q-hbar-row">
    <div class="q-hbar-row__top">
        <span class="q-hbar-row__label">{{ $label }}</span>
        <span class="q-hbar-row__value">{{ number_format($mean, 1, ',', '.') }}&nbsp;/&nbsp;{{ number_format($median, 1, ',', '.') }} {{ $unit }}</span>
    </div>
    <div class="q-hbar-track q-hbar-track--thin"><div class="q-hbar-fill" style="width:{{ $meanPercentage }}%"></div></div>
    <div class="q-hbar-track q-hbar-track--thin"><div class="q-hbar-fill q-hbar-fill--muted" style="width:{{ $medianPercentage }}%"></div></div>
</div>
