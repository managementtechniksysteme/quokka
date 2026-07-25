<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('memos.show', $memo) }}"></a>

    <span class="q-avatar">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#voicemail"></use></svg>
    </span>

    <div class="q-row__main">
        {{-- Title truncates, #number never does — same
             .q-row__title--numbered split as the numbered reports
             (service/additions/construction), applied here too since a long
             title could otherwise squeeze the number out entirely
             (2026-07-21, user). Shared at every breakpoint, not duplicated —
             the number no longer needs to live in the project/number chip
             below, so that chip drops it (and disappears entirely in the
             "no project" case, since the number was all it ever showed). --}}
        <div class="q-row__title q-row__title--numbered">
            <span class="q-row__title-main text-truncate">{{ $memo->title }}</span>
            <span class="q-row__sub q-mono flex-shrink-0">#{{ $memo->number }}</span>
        </div>

        {{-- Desktop: status + project + date + composer→recipient, unchanged
             apart from the number moving into the title above. --}}
        <div class="q-meta d-none d-md-flex">
            @if($memo->draft)
                <span class="q-status q-status--in-progress">Entwurf</span>
            @endif

            @if(($secondaryInformation ?? '') !== 'withoutProject')
                <span class="q-chip">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                    <span class="text-truncate">{{ optional($memo->project)->name ?? 'kein Projekt' }}</span>
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

        {{-- Mobile: pared down (same principle as task/person) — project
             alone on its own truncated line (only when there is one to
             show), then draft status + date on a second line.
             Composer/recipient drops entirely; still on the detail page. --}}
        <div class="d-md-none">
            @if(($secondaryInformation ?? '') !== 'withoutProject')
                <div class="q-meta mb-1">
                    <span class="q-chip">
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                        <span class="text-truncate">{{ optional($memo->project)->name ?? 'kein Projekt' }}</span>
                    </span>
                </div>
            @endif
            <div class="q-meta">
                @if($memo->draft)
                    <span class="q-status q-status--in-progress">Entwurf</span>
                @endif
                <span class="q-chip">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                    {{ $memo->meeting_held_on }}
                </span>
            </div>
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

    <svg class="icon-bs icon-16 q-row__chevron d-md-none"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-right"></use></svg>
</div>
