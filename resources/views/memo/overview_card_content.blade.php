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
                                {{ $memo->meeting_held_on }}
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
                                {{ $memo->meeting_held_on }}
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
                        {{ $memo->meeting_held_on }}
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
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="memoOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="memoOverviewDropdown">
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.edit', $memo) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email senden
                    </a>
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                        Favorisieren
                    </a>
                    <form action="{{ route('memos.destroy', $memo) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
