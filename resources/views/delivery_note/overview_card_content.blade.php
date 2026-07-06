<div class="q-row">
    <a class="stretched-link outline-none" href="{{ route('delivery-notes.show', $deliveryNote) }}"></a>

    <span class="q-avatar">
        <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#package"></use></svg>
    </span>

    <div class="q-row__main">
        <div class="q-row__title text-truncate">
            {{ $deliveryNote->title }}@unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject') <span class="q-row__sub">· {{ $deliveryNote->project->name }}</span>@endunless
        </div>
        <div class="q-meta">
            <span class="q-status q-status--{{ $deliveryNote->status }}">{{ $deliveryNote->status_label }}</span>

            <span class="q-chip">
                @switch($deliveryNote->status)
                    @case('new')
                        @if($deliveryNote->signatureRequest)
                            <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                            {{ $deliveryNote->signatureRequest->created_at }}
                        @else
                            <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use></svg>
                            {{ $deliveryNote->created_at }}
                        @endif
                        @break
                    @case('signed')
                        <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                        {{ $deliveryNote->signature()->created_at }}
                        @break
                    @case('finished')
                        <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use></svg>
                        {{ $deliveryNote->updated_at }}@if($deliveryNote->activities->last()) ({{ Str::upper($deliveryNote->activities->last()->causer->username) }})@endif
                        @break
                @endswitch
            </span>

            <span class="q-chip">
                <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use></svg>
                <span class="text-truncate">{{ $deliveryNote->employee->person->name }}</span>
            </span>
        </div>
    </div>

    {{-- kebab: same actions as before, lifted above the row's stretched-link --}}
    <div class="dropdown">
        <button class="q-kebab" type="button" id="deliveryNoteOverviewDropdown-{{ $deliveryNote->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryNoteOverviewDropdown-{{ $deliveryNote->id }}">
            @unless($deliveryNote->isFinished())
                @can('approve', $deliveryNote)
                    <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('delivery-notes.finish', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                        <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use></svg>
                        Erledigen
                    </a>
                @endcan
            @endunless
            @can('update', $deliveryNote)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.edit', $deliveryNote) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                    Bearbeiten
                </a>
            @endcan
            @can('email', $deliveryNote)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.email', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Email senden
                </a>
            @endcan
            @can('createPdf', $deliveryNote)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.download', $deliveryNote) }}" target="_blank">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                    PDF herunterladen
                </a>
            @endcan
            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                Favorisieren
            </a>
            @if(auth()->user()->can('sign', $deliveryNote) || auth()->user()->can('emailSignatureRequest', $deliveryNote) || auth()->user()->can('emailDownloadRequest', $deliveryNote))
                <div class="dropdown-divider"></div>
            @endif
            @can('sign', $deliveryNote)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.sign', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                    Unterschreiben lassen
                </a>
            @endcan
            @can('emailSignatureRequest', $deliveryNote)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.email-signature-request', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    Unterschrift Anfrage senden
                </a>
            @endcan
            @can('emailDownloadRequest', $deliveryNote)
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.email-download-request', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use></svg>
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
                        <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use></svg>
                        Entfernen
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>
