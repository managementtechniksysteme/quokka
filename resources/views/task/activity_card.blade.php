@php $isFinished = isset($activity->attribute_changes['attributes']['status']) && $activity->attribute_changes['attributes']['status'] === 'finished'; @endphp
<div class="q-comment q-comment--event">
    <span class="q-avatar q-avatar--round {{ $isFinished ? 'q-avatar--green' : 'q-avatar--muted' }}">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#{{ $isFinished ? 'check2-square' : 'activity' }}"></use></svg>
    </span>

    <div class="q-comment__head">
        <div class="q-comment__meta">
            <span class="q-comment__author">{{ $activity->causer->employee->person->name }}</span>
            <span class="q-comment__date q-mono">
                {{ $activity->created_at->format('d.m.Y · H:i') }}
            </span>
        </div>
    </div>

    <div class="q-comment__body">
        <div class="q-comment__bubble">
            @if(isset($activity->attribute_changes['attributes']['name']))
                <div class="q-change">
                    <div class="q-change__label">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                        Name
                    </div>
                    <div class="q-change__diff">
                        <del>{{ $activity->attribute_changes['old']['name'] }}</del>
                        <svg class="icon-bs icon-12 mx-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-right"></use></svg>
                        {{ $activity->attribute_changes['attributes']['name'] }}
                    </div>
                </div>
            @endif
            @if(isset($activity->attribute_changes['attributes']['project_id']))
                <div class="q-change">
                    <div class="q-change__label">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                        Projekt
                    </div>
                    <div class="q-change__diff">
                        <del>{{ \App\Models\Project::find($activity->attribute_changes['old']['project_id'])?->name ?? 'unbekannt' }}</del>
                        <svg class="icon-bs icon-12 mx-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-right"></use></svg>
                        {{ \App\Models\Project::find($activity->attribute_changes['attributes']['project_id'])?->name ?? 'unbekannt' }}
                    </div>
                </div>
            @endif
            @if(isset($activity->attribute_changes['attributes']['employee_id']))
                <div class="q-change">
                    <div class="q-change__label">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#person"></use></svg>
                        Verantwortliche Person
                    </div>
                    <div class="q-change__diff">
                        <del>{{ \App\Models\Person::find($activity->attribute_changes['old']['employee_id'])?->name ?? 'unbekannt' }}</del>
                        <svg class="icon-bs icon-12 mx-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-right"></use></svg>
                        {{ \App\Models\Person::find($activity->attribute_changes['attributes']['employee_id'])?->name ?? 'unbekannt' }}
                    </div>
                </div>
            @endif
            @if(isset($activity->attribute_changes['old']['involved_ids']) || isset($activity->attribute_changes['attributes']['involved_ids']))
                <div class="q-change">
                    <div class="q-change__label">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use></svg>
                        Mitwirkende Personen
                    </div>
                    <div class="q-change__diff">
                        <del>
                            @empty($activity->attribute_changes['old']['involved_ids'])
                                keine angegeben
                            @else
                                {{ \App\Models\Person::order()->find($activity->attribute_changes['old']['involved_ids'])->implode('name', ', ') }}
                            @endempty
                        </del>
                        <svg class="icon-bs icon-12 mx-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-right"></use></svg>
                        @empty($activity->attribute_changes['attributes']['involved_ids'])
                            keine angegeben
                        @else
                            {{ \App\Models\Person::order()->find($activity->attribute_changes['attributes']['involved_ids'])->implode('name', ', ') }}
                        @endempty
                    </div>
                </div>
            @endif
            @if(isset($activity->attribute_changes['old']['due_on']) || isset($activity->attribute_changes['attributes']['due_on']))
                <div class="q-change">
                    <div class="q-change__label">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                        Fälligkeitsdatum
                    </div>
                    <div class="q-change__diff">
                        <del>{{ isset($activity->attribute_changes['old']['due_on']) ? Carbon\Carbon::parse($activity->attribute_changes['old']['due_on']) : 'kein Datum' }}</del>
                        <svg class="icon-bs icon-12 mx-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-right"></use></svg>
                        {{ isset($activity->attribute_changes['attributes']['due_on']) ? Carbon\Carbon::parse($activity->attribute_changes['attributes']['due_on']) : 'kein Datum' }}
                    </div>
                </div>
            @endif
            @if(isset($activity->attribute_changes['old']['starts_on']) || isset($activity->attribute_changes['old']['ends_on']) || isset($activity->attribute_changes['attributes']['starts_on']) || isset($activity->attribute_changes['attributes']['ends_on']))
                <div class="q-change">
                    <div class="q-change__label">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                        Zeitraum
                    </div>
                    <div class="q-change__diff">
                        <del>
                            {{ isset($activity->attribute_changes['old']['starts_on']) ? Carbon\Carbon::parse($activity->attribute_changes['old']['starts_on']) : 'kein Start' }}
                            bis
                            {{ isset($activity->attribute_changes['old']['ends_on']) ? Carbon\Carbon::parse($activity->attribute_changes['old']['ends_on']) : 'kein Ende' }}
                        </del>
                        <svg class="icon-bs icon-12 mx-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-right"></use></svg>
                        {{ isset($activity->attribute_changes['attributes']['starts_on']) ? Carbon\Carbon::parse($activity->attribute_changes['attributes']['starts_on']) : 'kein Start' }}
                        bis
                        {{ isset($activity->attribute_changes['attributes']['ends_on']) ? Carbon\Carbon::parse($activity->attribute_changes['attributes']['ends_on']) : 'kein Ende' }}
                    </div>
                </div>
            @endif
            @if(isset($activity->attribute_changes['attributes']['priority']))
                <div class="q-change">
                    <div class="q-change__label">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
                        Priorität
                    </div>
                    <div class="q-change__diff">
                        <del>{{ trans($activity->attribute_changes['old']['priority']) }}</del>
                        <svg class="icon-bs icon-12 mx-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-right"></use></svg>
                        {{ trans($activity->attribute_changes['attributes']['priority']) }}
                    </div>
                </div>
            @endif
            @if(isset($activity->attribute_changes['attributes']['status']))
                <div class="q-change">
                    <div class="q-change__label">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#record-circle"></use></svg>
                        Status
                    </div>
                    <div class="q-change__diff">
                        <del>{{ trans($activity->attribute_changes['old']['status']) }}</del>
                        <svg class="icon-bs icon-12 mx-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-right"></use></svg>
                        {{ trans($activity->attribute_changes['attributes']['status']) }}
                    </div>
                </div>
            @endif
            @if(isset($activity->attribute_changes['attributes']['billed']))
                <div class="q-change">
                    <div class="q-change__label">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
                        Verrechnungsstatus
                    </div>
                    <div class="q-change__diff">
                        @switch($activity->attribute_changes['old']['billed'])
                            @case('yes') <del>{{ trans('billed') }}</del> @break
                            @case('no') <del>{{ trans('not billed') }}</del> @break
                            @case('warranty') <del>{{ trans('warranty') }}</del> @break
                        @endswitch
                        <svg class="icon-bs icon-12 mx-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-right"></use></svg>
                        @switch($activity->attribute_changes['attributes']['billed'])
                            @case('yes') {{ trans('billed') }} @break
                            @case('no') {{ trans('not billed') }} @break
                            @case('warranty') {{ trans('warranty') }} @break
                        @endswitch
                    </div>
                </div>
            @endif
            @if(isset($activity->attribute_changes['attributes']['private']))
                <div class="q-change">
                    <div class="q-change__label">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#lock"></use></svg>
                        Sichtbarkeitsstatus
                    </div>
                    <div class="q-change__diff">
                        <del>{{ trans($activity->attribute_changes['old']['private'] ? 'private' : 'public') }}</del>
                        <svg class="icon-bs icon-12 mx-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-right"></use></svg>
                        {{ trans($activity->attribute_changes['attributes']['private'] ? 'private' : 'public') }}
                    </div>
                </div>
            @endif
            @if(isset($activity->attribute_changes['attributes']['comment']))
                <div class="q-change">
                    <div class="q-change__label">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chat-dots"></use></svg>
                        Bemerkungen
                    </div>
                    <div class="q-change__diff">
                        <del>{{ Str::limit($activity->attribute_changes['old']['comment'], 20) }}</del>
                        <svg class="icon-bs icon-12 mx-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-right"></use></svg>
                        {{ Str::limit($activity->attribute_changes['attributes']['comment'], 20) }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
