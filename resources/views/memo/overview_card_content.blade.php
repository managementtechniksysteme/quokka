<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('memos.show', $memo) }}"></a>

    <span class="q-avatar">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#voicemail"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">{{ $memo->title }}</div>
        <div class="q-meta">
            @if($memo->draft)
                <span class="q-status q-status--in-progress">Entwurf</span>
            @endif

            @if(($secondaryInformation ?? '') !== 'withoutProject')
                <span class="q-chip">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                    <span class="text-truncate">{{ optional($memo->project)->name ?? 'kein Projekt' }}</span>
                    <span class="q-mono">#{{ $memo->number }}</span>
                </span>
            @else
                <span class="q-chip">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#hash"></use></svg>
                    <span class="q-mono">{{ $memo->number }}</span>
                </span>
            @endif

            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                {{ $memo->meeting_held_on }}
            </span>

            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use></svg>
                <span class="text-truncate">{{ $memo->employeeComposer->person->name }}@if($memo->personRecipient) → {{ $memo->personRecipient->name }}@endif</span>
            </span>
        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="memoOverviewDropdown-{{ $memo->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="memoOverviewDropdown-{{ $memo->id }}">
            @if($memo->draft)
                @can('update', $memo)
                    <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('memos.publish', ['memo' => $memo, 'redirect' => $actionRedirect ?? 'index']) }}">
                        <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                        Veröffentlichen
                    </a>
                @endcan
            @endif
            @can('update', $memo)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.edit', $memo) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('create', \App\Models\Memo::class)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.create', ['template' => $memo]) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#files"></use></svg>
                    Kopieren
                </a>
            @endcan
            @can('email', $memo)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.email', ['memo' => $memo, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $memo)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.download', $memo) }}" target="_blank">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                    PDF erstellen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @can('delete', $memo)
                <form action="{{ route('memos.destroy', ['memo' => $memo, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                        <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                        Entfernen
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>
