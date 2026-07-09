<div class="q-row">
    <span class="q-avatar">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $activity->properties['subject'] }}</div>
        <div class="q-meta">
            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                {{ $activity->created_at->format('d.m.Y H:i') }}
            </span>
            @if(!empty($activity->properties['to']))
                <span class="q-chip">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#send"></use></svg>
                    <span class="text-truncate">{{ Email::concatEmails($activity->properties['to']) }}</span>
                </span>
            @endif
            @if(!empty($activity->properties['cc']))
                <span class="q-chip"><span class="text-truncate">CC: {{ Email::concatEmails($activity->properties['cc']) }}</span></span>
            @endif
            @if(!empty($activity->properties['bcc']))
                <span class="q-chip"><span class="text-truncate">BCC: {{ Email::concatEmails($activity->properties['bcc']) }}</span></span>
            @endif
        </div>
    </div>
</div>
