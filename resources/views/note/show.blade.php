@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('note.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#book"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Notiz</div>
                    <h1 class="q-title">{{ $note->title ?? $note->comment }}</h1>
                    <div class="q-meta">
                        <span class="q-chip">
                            <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                            {{ $note->created_at->format('d.m.Y, H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @can('update', $note)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('notes.edit', $note) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="noteShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="noteShowDropdown">
                        @can('create', \App\Models\Note::class)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('notes.create', ['template' => $note]) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#files"></use></svg>
                                Kopieren
                            </a>
                        @endcan
                        @can('create', \App\Models\Task::class)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.create', ['note' => $note]) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                                Aufgabe erstellen
                            </a>
                        @endcan
                        @can('create', \App\Models\Memo::class)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.create', ['note' => $note]) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#voicemail"></use></svg>
                                Aktenvermerk erstellen
                            </a>
                        @endcan
                        @can('email', $note)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('notes.email', ['note' => $note, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $note)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('notes.download', $note) }}" target="_blank">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $note)
                            <form action="{{ route('notes.destroy', $note) }}" method="post">
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
        </div>

        <div class="q-card mb-3">
            <div class="q-card__head">Bemerkungen</div>
            <div class="q-card__body">
                <div class="markdown">
                    {!! Html::fromMarkdown($note->comment) !!}
                </div>
            </div>
        </div>

        @if($note->attachments()->count() > 0)
            <div class="q-card">
                <div class="q-card__head">Anhänge</div>
                <div class="q-card__body">
                    <div class="row g-2">
                        @foreach($note->attachments() as $attachment)
                            <div class="col-12 col-md-6 col-lg-4">
                                <a href="{{ $attachment->getUrl() }}" class="q-attach">
                                    @if($attachment->hasGeneratedConversion('thumbnail'))
                                        <img class="q-attach__preview" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                    @else
                                        <span class="q-attach__preview q-attach__preview--icon">
                                            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#file-text"></use></svg>
                                        </span>
                                    @endif
                                    <span class="min-w-0">
                                        <span class="q-attach__name text-truncate d-block">{{ $attachment->file_name }}</span>
                                        <span class="q-attach__size">{{ $attachment->human_readable_size }}</span>
                                    </span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
