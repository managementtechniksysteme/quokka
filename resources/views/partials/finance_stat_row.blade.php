@php $compact = $compact ?? false; @endphp
<div class="q-stat-row @if($compact) q-stat-row--compact @endif">
    @foreach($stats as $stat)
        <div class="q-stat">
            <div class="q-eyebrow">{{ $stat['label'] }}</div>
            <div class="q-mono fw-bold @if(!empty($stat['variant'])) q-stat__value--{{ $stat['variant'] }} @endif" style="font-size: {{ $compact ? '.95rem' : '1.5rem' }}">
                {{ Number::toLocal($stat['value'], 2) }} {{ $currencyUnit }}
            </div>
        </div>
    @endforeach
</div>
