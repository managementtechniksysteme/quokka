{{--
    Finance volume row: Ist-Kosten (red, actual incurred cost) vs.
    Verrechnet (green, already billed) bars, each scaled against $maxValue
    across the whole list, plus "Offen" (the difference — how much of the
    incurred cost isn't billed yet) as colored text. No third bar, the two
    bars already show the difference visually.
    Expects: $label, $costs, $billed, $open, $maxValue
--}}
@php
    $costsPercentage = $maxValue > 0 ? min(100, round($costs / $maxValue * 100, 1)) : 0;
    $billedPercentage = $maxValue > 0 ? min(100, round($billed / $maxValue * 100, 1)) : 0;
    $openColor = $open >= 0 ? 'var(--q-green)' : 'var(--q-red)';
@endphp
<div class="q-hbar-row">
    <div class="q-hbar-row__top">
        <span class="q-hbar-row__label">{{ $label }}</span>
        <span class="q-hbar-row__value">
            <span style="color:var(--q-red)">{{ number_format($costs, 0, ',', '.') }}&nbsp;€</span>&nbsp;/&nbsp;<span style="color:var(--q-green)">{{ number_format($billed, 0, ',', '.') }}&nbsp;€</span>
        </span>
    </div>
    <div class="q-hbar-track q-hbar-track--thin"><div class="q-hbar-fill" style="width:{{ $costsPercentage }}%; background:var(--q-red)"></div></div>
    <div class="q-hbar-track q-hbar-track--thin"><div class="q-hbar-fill" style="width:{{ $billedPercentage }}%; background:var(--q-green)"></div></div>
    <div class="q-finance-row__open" style="color:{{ $openColor }}">Offen {{ number_format($open, 0, ',', '.') }}&nbsp;€</div>
</div>
