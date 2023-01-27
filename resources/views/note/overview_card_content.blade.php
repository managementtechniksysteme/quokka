<div class="overview-card rounded">
    <div class="mw-100 d-flex flex-grow-1 p-3 align-items-center">
        <div class="mw-100 d-flex flex-grow-1 h-100 align-items-center">
            <div class="mw-100 flex-grow-1 position-relative">
                <a class="stretched-link outline-none" href="{{ route('notes.show', $note) }}"></a>
                <div class="mw-100 text-truncate">
                    {{ $note->title ?? $note->comment }}
                </div>
                <div class="text-muted mw-100">
                    <div class="d-flex d-md-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                        </svg>
                        {{ $note->created_at->format('d.m.Y, H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="d-none d-md-block ml-2">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="noteOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="noteOverviewDropdown">
                    @can('update', $note)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('notes.edit', $note) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('create', \App\Models\Note::class)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('notes.create', ['template' => $note]) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#copy"></use>
                            </svg>
                            Kopieren
                        </a>
                    @endcan
                    @can('create', \App\Models\Task::class)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.create', ['note' => $note]) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            Aufgabe erstellen
                        </a>
                    @endcan
                    @can('email', $note)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('notes.email', $note) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $note)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('notes.download', $note) }}">
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
                    @can('delete', $note)
                        <form action="{{ route('notes.destroy', $note) }}" method="post">
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
