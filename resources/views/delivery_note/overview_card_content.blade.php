<div class="overview-card rounded border-status @if($deliveryNote->isNew()) border-primary @elseif($deliveryNote->isSigned()) border-warning @else border-success @endif">
    <div class="mw-100 d-flex flex-grow-1 p-3 align-items-center">

        <div class="mw-100 flex-grow-1 h-100 position-relative">
            <a class="stretched-link outline-none" href="{{ route('delivery-notes.show', $deliveryNote) }}"></a>
            <div class="mw-100 text-truncate">
                {{ $deliveryNote->title }}
            </div>
            <div class="mw-100 text-muted">
                <div class="mw-100 d-inline-flex align-items-center">
                    @switch($deliveryNote->status)
                        @case('new')
                            @if($deliveryNote->signatureRequest)
                                <svg class="icon icon-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                                </svg>
                                {{ $deliveryNote->signatureRequest->created_at }}
                            @else
                                <svg class="icon icon-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                                </svg>
                                {{ $deliveryNote->created_at }}
                            @endif
                            @break
                        @case('signed')
                            <svg class="icon icon-16 ml-2 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            {{ $deliveryNote->signature()->created_at }}
                        @break
                        @case('finished')
                            <svg class="icon icon-16 ml-2 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            {{ $deliveryNote->updated_at }}
                            @if($deliveryNote->activities->last())
                                ({{ Str::upper($deliveryNote->activities->last()->causer->username) }})
                            @endif
                        @break
                    @endswitch
                    <svg class="icon icon-16 ml-2 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                    </svg>
                    <span class="mw-100 text-truncate">
                        {{ $deliveryNote->employee->person->name }}
                    </span>
                    <div class="mw-100 text-truncate">
                        @unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject')
                            <svg class="icon icon-16 mx-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                            </svg>
                            {{ $deliveryNote->project->name }}
                        @endunless
                    </div>
                </div>
            </div>
        </div>

        <div class="d-none d-md-block ml-2">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="deliveryNoteOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="deliveryNoteOverviewDropdown">
                    @unless($deliveryNote->isFinished())
                        @can('approve', $deliveryNote)
                            <a class="dropdown-item dropdown-item-success d-inline-flex align-items-center" href="{{ route('delivery-notes.finish', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                                </svg>
                                Erledigen
                            </a>
                        @endcan
                    @endunless
                    @can('update', $deliveryNote)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.edit', $deliveryNote) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('email', $deliveryNote)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.email', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $deliveryNote)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.download', $deliveryNote) }}" target="_blank">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                            </svg>
                            PDF herunterladen
                        </a>
                    @endcan
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                        Favorisieren
                    </a>
                    @if(auth()->user()->can('sign', $deliveryNote) || auth()->user()->can('emailSignatureRequest', $deliveryNote) || auth()->user()->can('emailDownloadRequest', $deliveryNote))
                        <div class="dropdown-divider"></div>
                    @endif
                    @can('sign', $deliveryNote)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.sign', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            Unterschreiben lassen
                        </a>
                    @endcan
                    @can('emailSignatureRequest', $deliveryNote)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.email-signature-request', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Unterschrift Anfrage senden
                        </a>
                    @endcan
                    @can('emailDownloadRequest', $deliveryNote)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.email-download-request', ['delivery_note' => $deliveryNote, 'redirect' => $actionRedirect ?? 'index']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use>
                            </svg>
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
