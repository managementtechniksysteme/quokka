@extends('layouts.app')

@section('content')
    <div class="q-container">

        @include('delivery_note.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#package"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Lieferschein</div>
                    <h1 class="q-title">{{ $deliveryNote->title }}</h1>
                    <div class="q-meta">
                        <span class="q-status q-status--{{ $deliveryNote->status }}">{{ $deliveryNote->status_label }}</span>

                        <span class="q-chip">
                            @switch($deliveryNote->status)
                                @case('signed')
                                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                                    {{ optional($signature)->created_at }}
                                    @break
                                @case('finished')
                                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use></svg>
                                    {{ $deliveryNote->updated_at }}@if($deliveryNote->activities->last()?->causer) · {{ Str::upper($deliveryNote->activities->last()->causer->username) }}@endif
                                    @break
                                @default
                                    @if($deliveryNote->signatureRequest)
                                        <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use></svg>
                                        {{ $deliveryNote->signatureRequest->created_at }}
                                    @else
                                        <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use></svg>
                                        {{ $deliveryNote->created_at }}
                                    @endif
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @unless($deliveryNote->isFinished())
                    @can('approve', $deliveryNote)
                        <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('delivery-notes.finish', ['delivery_note' => $deliveryNote, 'redirect' => 'show']) }}">
                            <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check"></use></svg>
                            Erledigen
                        </a>
                    @endcan
                @endunless
                @can('update', $deliveryNote)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('delivery-notes.edit', $deliveryNote) }}">
                        <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="deliveryNoteShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryNoteShowDropdown">
                        @can('email', $deliveryNote)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.email', ['delivery_note' => $deliveryNote, 'redirect' => 'show']) }}">
                                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $deliveryNote)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.download', $deliveryNote) }}" target="_blank">
                                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                                PDF herunterladen
                            </a>
                        @endcan
                        @can('emailSignatureRequest', $deliveryNote)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.email-signature-request', ['delivery_note' => $deliveryNote, 'redirect' => 'show']) }}">
                                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                                Unterschrift Anfrage senden
                            </a>
                        @endcan
                        @can('emailDownloadRequest', $deliveryNote)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('delivery-notes.email-download-request', ['delivery_note' => $deliveryNote, 'redirect' => 'show']) }}">
                                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use></svg>
                                Download Link senden
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $deliveryNote)
                            <form action="{{ route('delivery-notes.destroy', $deliveryNote) }}" method="post">
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
        </div>

        <div class="q-statbar mb-4">
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Projekt</span>
                <span class="q-statbar__value text-truncate">
                    <a href="{{ route('projects.show', $deliveryNote->project) }}">{{ $deliveryNote->project->name }}</a>
                </span>
            </div>
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Datum</span>
                <span class="q-statbar__value">{{ $deliveryNote->written_on }}</span>
            </div>
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Mitarbeiter</span>
                <span class="q-statbar__value">{{ $deliveryNote->employee->person->name }}</span>
            </div>
        </div>

        <div class="q-detail">
            <div class="d-flex flex-column gap-3">
                {{-- Bemerkungen --}}
                @if ($deliveryNote->comment)
                    <div class="q-card">
                        <div class="q-card__head">Bemerkungen</div>
                        <div class="q-card__body">
                            <div class="markdown">
                                {!! Html::fromMarkdown($deliveryNote->comment) !!}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Lieferschein (PDF) --}}
                <div class="q-card">
                    <div class="q-card__head">Lieferschein</div>
                    <div class="q-card__body">
                        <div class="q-signbox">
                            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use></svg>
                            Lieferschein als PDF
                        </div>
                        @can('createPdf', $deliveryNote)
                            <a class="btn q-btn w-100 d-inline-flex align-items-center justify-content-center gap-2" href="{{ route('delivery-notes.download', $deliveryNote) }}" target="_blank">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                                PDF herunterladen
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

            {{-- Unterschrift --}}
            <div class="q-card q-card--quiet">
                <div class="q-card__head d-flex align-items-center justify-content-between">
                    <span>Unterschrift</span>
                    @if($signature)
                        <span class="q-chip q-chip--success">
                            <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check"></use></svg>
                            vorhanden
                        </span>
                    @else
                        <span class="q-chip q-chip--warning">
                            <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use></svg>
                            ausstehend
                        </span>
                    @endif
                </div>
                <div class="q-card__body">
                    @if($signature)
                        <img class="q-sign-img" src="{{ $signature->getUrl() }}" alt="Unterschrift" />
                        <div class="q-sign-date">unterschrieben am {{ $signature->created_at }}</div>
                    @else
                        <div class="q-signbox">
                            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                            Keine Unterschrift
                        </div>
                        @can('sign', $deliveryNote)
                            <a class="btn btn-primary text-white w-100 d-inline-flex align-items-center justify-content-center gap-2" href="{{ route('delivery-notes.sign', ['delivery_note' => $deliveryNote, 'redirect' => 'show']) }}">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                                Unterschreiben lassen
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
