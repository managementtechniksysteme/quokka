@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('note.breadcrumb')

            <h3>
                Notiz
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $note->created_at->format('d.m.Y, H:i') }}
                    @if(false)
                        <svg class="icon-bs icon-16 text-yellow ms-1">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @can('update', $note)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('notes.edit', $note) }}">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('create', \App\Models\Note::class)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('notes.create', ['template' => $note]) }}">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#files"></use>
                        </svg>
                        Kopieren
                    </a>
                @endcan
                @can('create', \App\Models\Task::class)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('tasks.create', ['note' => $note]) }}">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use>
                        </svg>
                        Aufgabe erstellen
                    </a>
                @endcan
                @can('create', \App\Models\Memo::class)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('memos.create', ['note' => $note]) }}">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#voicemail"></use>
                        </svg>
                        Aktenvermerk erstellen
                    </a>
                @endcan
                @can('email', $note)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('notes.email', ['note' => $note, 'redirect' => 'show']) }}">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $note)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('notes.download', $note) }}" target="_blank">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                @endcan
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="icon-bs icon-16 me-2">
                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                @can('delete', $note)
                    <form action="{{ route('notes.destroy', $note) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                            <svg class="icon-bs icon-16 me-2">
                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                @endcan
            </div>

        </div>
    </div>

    <div class="container my-4">

        <div class="row">
            <div class="col-sm-5 col-md-4 col-lg-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon-bs icon-16 me-2">
                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use>
                    </svg>
                    Datum
                </div>
            </div>
            <div class="col-sm-7">
                {{ $note->created_at->format('d.m.Y, H:i')  }}
            </div>
        </div>

        @if($note->title)
            <div class="row mt-3">
                <div class="col-sm-5 col-md-4 col-lg-2">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#book"></use>
                        </svg>
                        Titel
                    </div>
                </div>
                <div class="col-sm-7">
                    {{ $note->title  }}
                </div>
            </div>
        @endif

        <div class="text-muted d-flex align-items-center mt-4">
            <svg class="icon-bs icon-16 me-2">
                <use href="{{ asset('svg/bootstrap-icons.svg') }}#chat-dots"></use>
            </svg>
            Bemerkungen
        </div>
        <div class="markdown">
            {!! Html::fromMarkdown($note->comment) !!}
        </div>

        @if($note->attachments()->count() > 0)
            <div class="row text-muted d-flex align-items-center mt-1">
                <div class="col">
                    <div class="d-none d-md-inline-flex align-items-center">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#paperclip"></use>
                        </svg>
                        Anhänge
                    </div>
                    <a class="d-inline-flex d-md-none d-inline-flex align-items-center" data-bs-toggle="collapse" href="#collapseNoteAttachments-{{ $note->id }}" role="button" aria-expanded="false" aria-controls="collapseNoteAttachments-{{ $note->id }}">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#paperclip"></use>
                        </svg>
                        Anhänge
                    </a>
                </div>
            </div>
            <div class="d-none d-md-block">
                <div class="row">
                    @foreach($note->attachments() as $attachment)
                        <div class="col-12 col-md-6 col-lg-3 mt-1">
                            <div class="attachment bg-gray-100 border border-gray-300 d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                                @if($attachment->hasGeneratedConversion('thumbnail'))
                                    <img class="attachment-img-preview me-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                @else
                                    <svg class="icon-bs attachment-img-preview me-2">
                                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#file-text"></use>
                                    </svg>
                                @endif
                                <div class="min-w-0">
                                    <div class="min-w-0 text-truncate">{{ $attachment->file_name }}</div>
                                    <div class="text-muted">{{ $attachment->human_readable_size }}</div>
                                </div>
                                <a href="{{ $attachment->getUrl() }}" class="stretched-link outline-none"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="collapse d-md-none" id="collapseNoteAttachments-{{ $note->id }}">
                <div class="row">
                    @foreach($note->attachments() as $attachment)
                        <div class="col-12 col-md-6 col-lg-3 mt-1">
                            <div class="attachment bg-gray-100 border border-gray-300 d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                                @if($attachment->hasGeneratedConversion('thumbnail'))
                                    <img class="attachment-img-preview me-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                @else
                                    <svg class="icon-bs attachment-img-preview me-2">
                                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#file-text"></use>
                                    </svg>
                                @endif
                                <div class="min-w-0">
                                    <div class="min-w-0 text-truncate">{{ $attachment->file_name }}</div>
                                    <div class="text-muted">{{ $attachment->human_readable_size }}</div>
                                </div>
                                <a href="{{ $attachment->getUrl() }}" class="stretched-link outline-none"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection
