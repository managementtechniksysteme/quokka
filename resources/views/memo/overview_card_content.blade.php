<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('memos.show', $memo) }}"></a>
            <div class="min-w-100 text-truncate">
                {{ $memo->title }}
            </div>
            <div class="text-muted">
                @if(isset($secondaryInformation))
                    @switch($secondaryInformation)
                        @case('withoutProject')
                            <div class="d-flex d-md-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#hash"></use>
                                </svg>
                                {{ $memo->number }}
                                <svg class="text-muted icon icon-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                                </svg>
                                {{ $memo->meeting_held_on }}
                            </div>
                            <div class="d-flex d-md-inline-flex align-items-center">
                                <svg class="icon icon-16 ml-md-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                </svg>
                                {{ $memo->employeeComposer->person->name }}
                                <svg class="icon icon-16 mx-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                                </svg>
                                <span class="min-w-0 text-truncate">
                                    {{  optional($memo->personRecipient)->name ?? 'kein Empfänger' }}
                                </span>
                            </div>
                            @break
                        @default
                            <div class="d-flex d-md-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#hash"></use>
                                </svg>
                                {{ $memo->number }}
                                <svg class="icon icon-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                                </svg>
                                <span class="min-w-0 text-truncate">
                                    {{ $memo->project->name }}
                                </span>
                            </div>
                            <div class="d-flex d-md-inline-flex align-items-center">
                                <svg class="text-muted icon icon-16 ml-md-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                                </svg>
                                {{ $memo->meeting_held_on }}
                                <svg class="icon icon-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                </svg>
                                {{ $memo->employeeComposer->person->name }}
                                <svg class="icon icon-16 mx-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                                </svg>
                                <span class="min-w-0 text-truncate">
                                    {{  optional($memo->personRecipient)->name ?? 'kein Empfänger' }}
                                </span>
                            </div>
                            @break
                    @endswitch()
                @else
                    <div class="d-flex d-md-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#hash"></use>
                        </svg>
                        {{ $memo->number }}
                        <svg class="icon icon-16 ml-2 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                        </svg>
                        <span class="min-w-0 text-truncate">
                            {{ $memo->project->name }}
                        </span>
                    </div>
                    <div class="d-flex d-md-inline-flex align-items-center">
                        <svg class="text-muted icon icon-16 ml-md-2 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                        </svg>
                        {{ $memo->meeting_held_on }}
                        <svg class="icon icon-16 ml-2 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                        </svg>
                        {{ $memo->employeeComposer->person->name }}
                        <svg class="icon icon-16 mx-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                        </svg>
                        <span class="min-w-0 text-truncate">
                            {{  optional($memo->personRecipient)->name ?? 'kein Empfänger' }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="memoOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="memoOverviewDropdown">
                    @can('update', $memo)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.edit', $memo) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('create', \App\Models\Memo::class)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.create', ['template' => $memo]) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#copy"></use>
                            </svg>
                            Kopieren
                        </a>
                    @endcan
                    @can('email', $memo)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.email', ['memo' => $memo, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $memo)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.download', $memo) }}" target="_blank">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                            </svg>
                            PDF erstellen
                        </a>
                    @endcan
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                        Favorisieren
                    </a>
                    @can('delete', $memo)
                        <form action="{{ route('memos.destroy', ['memo' => $memo, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                                </svg>
                                Entfernen
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>

    </div>
</div>
