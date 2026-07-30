{{--
    Employee workload row (utilisation card). Expects: $row (employee, task_count,
    relative_to_busiest, share_of_team) and $value (the percentage for the
    currently active tab).
--}}
@php
    $employee = $row->employee;
    $color = $value >= 85 ? 'var(--q-red)' : ($value >= 60 ? 'var(--q-amber)' : 'var(--q-green)');
@endphp
<div class="q-people-row">
    <span class="q-avatar q-avatar--round @unless($employee->user?->avatar_colour_hex) q-avatar--{{ $employee->person->avatar_colour }} @endunless" @if($employee->user?->avatar_colour_hex) style="background: color-mix(in srgb, {{ $employee->user->avatar_colour_hex }} 20%, transparent); color: {{ $employee->user->avatar_colour_hex }};" @endif>{{ $employee->user ? $employee->user->username_avatar_string : $employee->person->initials }}</span>
    <div class="q-people-row__main">
        <div class="q-people-row__name">{{ $employee->person->name }}</div>
        <div class="q-people-row__meta">
            <span class="q-chip">{{ $row->task_count }} Aufgaben</span>
        </div>
    </div>
    <div class="q-people-row__load">
        <div class="q-people-row__load-top"><span>Auslastung</span><span style="color:{{ $color }}">{{ $value }}%</span></div>
        <div class="q-hbar-track q-hbar-track--thin"><div class="q-hbar-fill" style="width:{{ $value }}%; background:{{ $color }}"></div></div>
    </div>
</div>
