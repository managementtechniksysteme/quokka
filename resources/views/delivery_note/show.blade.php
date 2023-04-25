@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('delivery_note.breadcrumb')

            <h3>
                Lieferschein
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $deliveryNote->title }}
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @unless($deliveryNote->isFinished())
                    @can('approve', $deliveryNote)
                        <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('delivery-notes.finish', ['delivery_note' => $deliveryNote, 'redirect' => 'show']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            Erledigen
                        </a>
                    @endcan
                @endunless
                @can('update', $deliveryNote)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('delivery-notes.edit', $deliveryNote) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('email', $deliveryNote)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('delivery-notes.email', ['delivery_note' => $deliveryNote, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $deliveryNote)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('delivery-notes.download', $deliveryNote) }}" target="_blank">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF herunterladen
                    </a>
                @endcan
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                @can('sign', $deliveryNote)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('delivery-notes.sign', ['delivery_note' => $deliveryNote, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                        </svg>
                        Unterschreiben lassen
                    </a>
                @endcan
                @can('emailSignatureRequest', $deliveryNote)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('delivery-notes.email-signature-request', ['delivery_note' => $deliveryNote, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Unterschrift Anfrage senden
                    </a>
                @endcan
                @can('emailDownloadRequest', $deliveryNote)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('delivery-notes.email-download-request', ['delivery_note' => $deliveryNote, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use>
                        </svg>
                        Download Link senden
                    </a>
                @endcan
                @can('delete', $deliveryNote)
                    <form action="{{ route('delivery-notes.destroy', $deliveryNote) }}" method="post" >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
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

    <div class="container my-4">
        <div class="row mt-3 mt-md-4">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                    </svg>
                    Projekt
                </div>
            </div>
            <div class="col">
                <a href="{{ route('projects.show', $deliveryNote->project) }}">{{ $deliveryNote->project->name }}</a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                    </svg>
                    Mitarbeiter
                </div>
            </div>
            <div class="col">
                {{ $deliveryNote->employee->person->name }}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="@if($deliveryNote->isNew()) text-primary @elseif($deliveryNote->isSigned()) text-warning @else text-success @endif icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#git-commit"></use>
                    </svg>
                    Status
                </div>
            </div>
            <div class="col">
                {{ __($deliveryNote->status) }}
                @switch($deliveryNote->status)
                    @case('new')
                        @if($deliveryNote->signatureRequest)
                            (Anfrage zur Unterschrift gesendet am {{ $deliveryNote->signatureRequest->created_at }})
                        @else
                            (erstellt am {{ $deliveryNote->created_at }})
                        @endif
                        @break
                    @case('signed')
                        am {{ $deliveryNote->signature()->created_at }}
                        @break
                    @case('finished')
                        am {{ $deliveryNote->updated_at }}
                        @if($deliveryNote->activities->last())
                            ({{ Str::upper($deliveryNote->activities->last()->causer->username) }})
                        @endif
                        @break
                @endswitch
            </div>
        </div>

        @if ($deliveryNote->comment)
            <div class="text-muted d-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                </svg>
                Bemerkungen
            </div>
            <div class="markdown">
                {!! Html::fromMarkdown($deliveryNote->comment) !!}
            </div>
        @endif

    </div>

@endsection
