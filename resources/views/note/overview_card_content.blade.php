<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('notes.show', $note) }}"></a>

    <span class="q-avatar">
        <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#book"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $note->title ?? $note->comment }}</div>
        <div class="q-meta">
            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use></svg>
                {{ $note->created_at->format('d.m.Y, H:i') }}
            </span>
        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="noteOverviewDropdown-{{ $note->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="noteOverviewDropdown-{{ $note->id }}">
            @can('update', $note)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('notes.edit', $note) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('create', \App\Models\Note::class)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('notes.create', ['template' => $note]) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#copy"></use></svg>
                    Kopieren
                </a>
            @endcan
            @can('create', \App\Models\Task::class)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.create', ['note' => $note]) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use></svg>
                    Aufgabe erstellen
                </a>
            @endcan
            @can('create', \App\Models\Memo::class)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.create', ['note' => $note]) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use></svg>
                    Aktenvermerk erstellen
                </a>
            @endcan
            @can('email', $note)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('notes.email', $note) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $note)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('notes.download', $note) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @can('delete', $note)
                <form action="{{ route('notes.destroy', $note) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                        <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use></svg>
                        Entfernen
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>
