<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('delivery-notes.show', $deliveryNote) }}"></a>

    <span class="q-avatar">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#box-seam"></use></svg>
    </span>

    <div class="q-row__main">
        {{-- Desktop: title · project subheading, unchanged. --}}
        <div class="q-row__title text-truncate d-none d-md-block">
            {{ $deliveryNote->title }}@unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject') <span class="q-row__sub">· {{ $deliveryNote->project->name }}</span>@endunless
        </div>
        {{-- Mobile: title alone — project moves to its own chip below
             instead of being squeezed into the truncated title line
             (2026-07-21, user: same "truncated chip" treatment as other
             modules — avoids the project name getting silently clipped if
             the delivery note's own title is already long enough to fill
             the line by itself, same risk class as the numbered-report
             title bug fixed earlier this session). --}}
        <div class="q-row__title text-truncate d-md-none">{{ $deliveryNote->title }}</div>

        @unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject')
            <div class="q-meta mb-1 d-md-none">
                <span class="q-chip">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                    <span class="text-truncate">{{ $deliveryNote->project->name }}</span>
                </span>
            </div>
        @endunless

        {{-- Desktop: status + contextual status-date + technician, unchanged. --}}
        <div class="q-meta d-none d-md-flex">
            <span class="q-status q-status--{{ $deliveryNote->status }}">{{ $deliveryNote->status_label }}</span>

            <span class="q-chip">
                @switch($deliveryNote->status)
                    @case('new')
                        @if($deliveryNote->signatureRequest)
                            <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                            {{ $deliveryNote->signatureRequest->created_at }}
                        @else
                            <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            {{ $deliveryNote->created_at }}
                        @endif
                        @break
                    @case('signed')
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                        {{ $deliveryNote->signature()->created_at }}
                        @break
                    @case('finished')
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                        {{ $deliveryNote->updated_at }}@if($deliveryNote->activities->last()) ({{ Str::upper($deliveryNote->activities->last()->causer->username) }})@endif
                        @break
                @endswitch
            </span>

            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#person"></use></svg>
                <span class="text-truncate">{{ $deliveryNote->employee->person->name }}</span>
            </span>
        </div>

        {{-- Mobile: technician/"who" chip drops; status + status-date are
             their own line below the project chip above. --}}
        <div class="q-meta d-md-none">
            <span class="q-status q-status--{{ $deliveryNote->status }}">{{ $deliveryNote->status_label }}</span>

            <span class="q-chip">
                @switch($deliveryNote->status)
                    @case('new')
                        @if($deliveryNote->signatureRequest)
                            <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                            {{ $deliveryNote->signatureRequest->created_at }}
                        @else
                            <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            {{ $deliveryNote->created_at }}
                        @endif
                        @break
                    @case('signed')
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                        {{ $deliveryNote->signature()->created_at }}
                        @break
                    @case('finished')
                        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                        {{ $deliveryNote->updated_at }}@if($deliveryNote->activities->last()) ({{ Str::upper($deliveryNote->activities->last()->causer->username) }})@endif
                        @break
                @endswitch
            </span>
        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="deliveryNoteOverviewDropdown-{{ $deliveryNote->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryNoteOverviewDropdown-{{ $deliveryNote->id }}">
            @unless($deliveryNote->isFinished())
                @can('approve', $deliveryNote)
                    <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('delivery-notes.finish', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                        <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                        Erledigen
                    </a>
                @endcan
            @endunless
            @can('update', $deliveryNote)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.edit', $deliveryNote) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('email', $deliveryNote)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.email', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $deliveryNote)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.download', $deliveryNote) }}" target="_blank">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                    PDF herunterladen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @if(auth()->user()->can('sign', $deliveryNote) || auth()->user()->can('emailSignatureRequest', $deliveryNote) || auth()->user()->can('emailDownloadRequest', $deliveryNote))
                <div class="dropdown-divider"></div>
            @endif
            @can('sign', $deliveryNote)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.sign', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg>
                    Unterschreiben lassen
                </a>
            @endcan
            @can('emailSignatureRequest', $deliveryNote)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.email-signature-request', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                    Unterschrift Anfrage senden
                </a>
            @endcan
            @can('emailDownloadRequest', $deliveryNote)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.email-download-request', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#download"></use></svg>
                    Download Link senden
                </a>
            @endcan
            @if(auth()->user()->can('delete', $deliveryNote) && (auth()->user()->can('sign', $deliveryNote) || auth()->user()->can('emailSignatureRequest', $deliveryNote) || auth()->user()->can('emailDownloadRequest', $deliveryNote)))
                <div class="dropdown-divider"></div>
            @endif
            @can('delete', $deliveryNote)
                <form action="{{ route('delivery-notes.destroy', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
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
