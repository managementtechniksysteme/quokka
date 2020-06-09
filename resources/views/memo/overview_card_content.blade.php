<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('memos.show', $memo) }}"></a>
            <div>
                {{ $memo->title }}
            </div>
            <div class="text-muted">
                @if(isset($secondaryInformation))
                    @switch($secondaryInformation)
                        @case('withoutProject')
                            <div class="d-flex d-md-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#hash"></use>
                                </svg>
                                {{ $memo->number }}
                                <svg class="text-muted feather feather-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                                </svg>
                                {{ $memo->meeting_held_on->format('d.m.Y') }}
                            </div>
                            <div class="d-flex d-md-inline-flex align-items-center">
                                <svg class="feather feather-16 ml-md-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                </svg>
                                {{ $memo->employeeComposer->person->name }}
                                <svg class="feather feather-16 mx-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                                </svg>
                                {{ optional($memo->personRecipient)->name ?? 'kein Empfänger angegeben' }}
                            </div>
                            @break
                        @default
                            <div class="d-flex d-md-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#hash"></use>
                                </svg>
                                {{ $memo->number }}
                                <svg class="feather feather-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                                </svg>
                                {{ $memo->project->name }}
                            </div>
                            <div class="d-flex d-md-inline-flex align-items-center">
                                <svg class="text-muted feather feather-16 ml-md-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                                </svg>
                                {{ $memo->meeting_held_on->format('d.m.Y') }}
                                <svg class="feather feather-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                </svg>
                                {{ $memo->employeeComposer->person->name }}
                                <svg class="feather feather-16 mx-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                                </svg>
                                {{ optional($memo->personRecipient)->name ?? 'kein Empfänger angegeben' }}
                            </div>
                            @break
                    @endswitch()
                @else
                    <div class="d-flex d-md-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#hash"></use>
                        </svg>
                        {{ $memo->number }}
                        <svg class="feather feather-16 ml-2 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                        </svg>
                        {{ $memo->project->name }}
                    </div>
                    <div class="d-flex d-md-inline-flex align-items-center">
                        <svg class="text-muted feather feather-16 ml-md-2 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                        </svg>
                        {{ $memo->meeting_held_on->format('d.m.Y') }}
                        <svg class="feather feather-16 ml-2 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                        </svg>
                        {{ $memo->employeeComposer->person->name }}
                        <svg class="feather feather-16 mx-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                        </svg>
                        {{  optional($memo->personRecipient)->name ?? 'kein Empfänger angegeben' }}
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="companyOverviewDropdown" data-toggle="dropdown"></button>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.edit', $memo) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Aktenvermerk bearbeiten
                    </a>

                    <form action="{{ route('memos.destroy', $memo) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Aktenvermerk entfernen
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
