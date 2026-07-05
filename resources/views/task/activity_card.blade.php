<div class="py-2">
    <div class="row">
        <div class="col-auto d-none d-md-block pe-0">
            <div class="avatar @if(isset($activity->attribute_changes['attributes']['status']) && $activity->attribute_changes['attributes']['status'] === 'finished') bg-green-200 @else bg-yellow-200 @endif rounded-circle border @if(isset($activity->attribute_changes['attributes']['status']) && $activity->attribute_changes['attributes']['status'] === 'finished') border-green-800 @else border-yellow-800 @endif d-inline-flex align-items-center justify-content-center">
                <h4 class="@if(isset($activity->attribute_changes['attributes']['status']) && $activity->attribute_changes['attributes']['status'] === 'finished') text-green-800 @else text-yellow-800 @endif m-0">
                    <svg class="icon icon-baseline">
                        @if(isset($activity->attribute_changes['attributes']['status']) && $activity->attribute_changes['attributes']['status'] === 'finished')
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                        @else
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#activity"></use>
                        @endif
                    </svg>
                </h4>
            </div>
        </div>
        <div class="col">
            <div class="rounded-top border @if(isset($activity->attribute_changes['attributes']['status']) && $activity->attribute_changes['attributes']['status'] === 'finished') border-activity-finished-header @endif bg-gray-100 px-2 py-1">
                <div class="row">
                    <div class="col-auto me-auto">
                        <div class="lead">{{ $activity->causer->employee->person->name }}</div>
                        <p class="text-muted d-inline-flex align-items-center m-0">
                            <svg class="icon icon-16 me-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                            </svg>
                            {{ $activity->created_at->format('d.m.Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-bottom border @if(isset($activity->attribute_changes['attributes']['status']) && $activity->attribute_changes['attributes']['status'] === 'finished') border-activity-finished-body @endif border-top-0 px-2 py-2">
                <div class="row">
                    <div class="col">
                        @if(isset($activity->attribute_changes['attributes']['name']))
                            <div class="text-muted">
                                <svg class="icon icon-baseline me-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                                </svg>
                                Name
                            </div>
                            <del>{{ $activity->attribute_changes['old']['name'] }}</del>
                            <svg class="icon icon-baseline m-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                            </svg>
                            {{ $activity->attribute_changes['attributes']['name'] }}
                        @endif
                        @if(isset($activity->attribute_changes['attributes']['project_id']))
                            <div class="text-muted">
                                <svg class="icon icon-baseline me-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                                </svg>
                                Projekt
                            </div>
                            <del>{{ \App\Models\Project::find($activity->attribute_changes['old']['project_id'])?->name ?? 'unbekannt' }}</del>
                            <svg class="icon icon-baseline m-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                            </svg>
                            {{ \App\Models\Project::find($activity->attribute_changes['attributes']['project_id'])?->name ?? 'unbekannt' }}
                        @endif
                        @if(isset($activity->attribute_changes['attributes']['employee_id']))
                            <div class="text-muted">
                                <svg class="icon icon-baseline me-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                                </svg>
                                Verantwortliche Person
                            </div>
                            <del>{{ \App\Models\Person::find($activity->attribute_changes['old']['employee_id'])?->name ?? 'unbekannt' }}</del>
                            <svg class="icon icon-baseline m-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                            </svg>
                            {{ \App\Models\Person::find($activity->attribute_changes['attributes']['employee_id'])?->name ?? 'unbekannt' }}
                        @endif
                        @if(isset($activity->attribute_changes['old']['involved_ids']) || isset($activity->attribute_changes['attributes']['involved_ids']))
                            <div class="text-muted">
                                <svg class="icon icon-baseline me-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                </svg>
                                Mitwirkende Personen
                            </div>
                            <del>
                                @empty($activity->attribute_changes['old']['involved_ids'])
                                    keine angegeben
                                @else
                                    {{ \App\Models\Person::order()->find($activity->attribute_changes['old']['involved_ids'])->implode('name', ', ') }}
                                @endempty
                            </del>
                            <svg class="icon icon-baseline m-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                            </svg>
                            @empty($activity->attribute_changes['attributes']['involved_ids'])
                                keine angegeben
                            @else
                                {{ \App\Models\Person::order()->find($activity->attribute_changes['attributes']['involved_ids'])->implode('name', ', ') }}
                            @endempty
                        @endif
                        @if(isset($activity->attribute_changes['old']['due_on']) || isset($activity->attribute_changes['attributes']['due_on']))
                            <div class="text-muted">
                                <svg class="icon icon-baseline me-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                                </svg>
                                Fälligkeitsdatum
                            </div>
                            <del>{{ Carbon\Carbon::parse($activity->attribute_changes['old']['due_on']) ?? 'kein Datum' }}</del>
                            <svg class="icon icon-baseline m-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                            </svg>
                            {{ Carbon\Carbon::parse($activity->attribute_changes['attributes']['due_on']) ?? 'kein Datum' }}
                        @endif
                        @if(isset($activity->attribute_changes['old']['starts_on']) || isset($activity->attribute_changes['old']['ends_on']) || isset($activity->attribute_changes['attributes']['starts_on']) || isset($activity->attribute_changes['attributes']['ends_on']))
                            <div class="text-muted">
                                <svg class="icon icon-baseline me-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                                </svg>
                                Zeitraum
                            </div>
                            <del>
                                {{ isset($activity->attribute_changes['old']['starts_on']) ? Carbon\Carbon::parse($activity->attribute_changes['old']['starts_on']) : 'kein Start' }}
                                bis
                                {{ isset($activity->attribute_changes['old']['ends_on']) ? Carbon\Carbon::parse($activity->attribute_changes['old']['ends_on']) : 'kein Ende' }}
                            </del>
                            <svg class="icon icon-baseline m-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                            </svg>
                            {{ isset($activity->attribute_changes['attributes']['starts_on']) ? Carbon\Carbon::parse($activity->attribute_changes['attributes']['starts_on']) : 'kein Start' }}
                            bis
                            {{ isset($activity->attribute_changes['attributes']['ends_on']) ? Carbon\Carbon::parse($activity->attribute_changes['attributes']['ends_on']) : 'kein Ende' }}
                        @endif
                        @if(isset($activity->attribute_changes['attributes']['priority']))
                            <div class="text-muted">
                                <svg class="icon icon-baseline me-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                                </svg>
                                Priorität
                            </div>
                            <del>{{ trans($activity->attribute_changes['old']['priority']) }}</del>
                            <svg class="icon icon-baseline m-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                            </svg>
                            {{ trans($activity->attribute_changes['attributes']['priority']) }}
                        @endif
                        @if(isset($activity->attribute_changes['attributes']['status']))
                            <div class="text-muted">
                                <svg class="icon icon-baseline me-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#git-commit"></use>
                                </svg>
                                Status
                            </div>
                            <del>{{ trans($activity->attribute_changes['old']['status']) }}</del>
                            <svg class="icon icon-baseline m-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                            </svg>
                            {{ trans($activity->attribute_changes['attributes']['status']) }}
                        @endif
                        @if(isset($activity->attribute_changes['attributes']['billed']))
                            <div class="text-muted">
                                <svg class="icon icon-baseline me-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                                </svg>
                                Verrechnungsstatus
                            </div>
                            @switch($activity->attribute_changes['old']['billed'])
                                @case('yes')
                                    <del>{{ trans('billed') }}</del>
                                    @break
                                @case('no')
                                    <del>{{ trans('not billed') }}</del>
                                    @break
                                @case('warranty')
                                    <del>{{ trans('warranty') }}</del>
                                    @break
                            @endswitch
                            <svg class="icon icon-baseline m-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                            </svg>
                            @switch($activity->attribute_changes['attributes']['billed'])
                                @case('yes')
                                    {{ trans('billed') }}
                                    @break
                                @case('no')
                                    {{ trans('not billed') }}
                                    @break
                                @case('warranty')
                                    {{ trans('warranty') }}
                                    @break
                            @endswitch
                        @endif
                        @if(isset($activity->attribute_changes['attributes']['private']))
                            <div class="text-muted">
                                <svg class="icon icon-baseline me-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#lock"></use>
                                </svg>
                                Sichtbarkeitsstatus
                            </div>
                            <del>{{ trans($activity->attribute_changes['old']['private'] ? 'private' : 'public') }}</del>
                            <svg class="icon icon-baseline m-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                            </svg>
                            {{ trans($activity->attribute_changes['attributes']['private'] ? 'private' : 'public') }}
                        @endif
                        @if(isset($activity->attribute_changes['attributes']['comment']))
                            @if(isset($activity->attribute_changes['attributes']['comment']))
                                <div class="text-muted">
                                    <svg class="icon icon-baseline me-1">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                                    </svg>
                                    Bemerkungen
                                </div>
                                <del>{{ Str::limit($activity->attribute_changes['old']['comment'], 20) }}</del>
                                <svg class="icon icon-baseline m-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                                </svg>
                                {{ Str::limit($activity->attribute_changes['attributes']['comment'], 20) }}
                            @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
