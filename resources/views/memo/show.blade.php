@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('memos.index') }}" class="q-appbar__btn" aria-label="Zurück zu Aktenvermerken">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $memo->title }}</span>
    <button class="q-appbar__btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#memoShowActionsSheet" aria-controls="memoShowActionsSheet" aria-label="Aktionen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
    </button>
@endsection

@section('mobile-detail-sheets')
    <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="memoShowActionsSheet" aria-label="Aktionen">
        <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
        <div class="offcanvas-body">
            <div class="q-sheet__label">Aktionen</div>
            @if($memo->draft)
                @can('update', $memo)
                    <a class="q-row" href="{{ route('memos.publish', ['memo' => $memo, 'redirect' => 'show']) }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg></span>
                        <span class="q-row__title">Veröffentlichen</span>
                    </a>
                @endcan
            @endif
            @can('update', $memo)
                <a class="q-row" href="{{ route('memos.edit', $memo) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg></span>
                    <span class="q-row__title">Bearbeiten</span>
                </a>
            @endcan
            @can('create', \App\Models\Memo::class)
                <a class="q-row" href="{{ route('memos.create', ['template' => $memo]) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#files"></use></svg></span>
                    <span class="q-row__title">Kopieren</span>
                </a>
            @endcan
            @can('email', $memo)
                <a class="q-row" href="{{ route('memos.email', ['memo' => $memo, 'redirect' => 'show']) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg></span>
                    <span class="q-row__title">Email versenden</span>
                </a>
            @endcan
            @can('createPdf', $memo)
                <a class="q-row" href="{{ route('memos.download', $memo) }}" target="_blank">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg></span>
                    <span class="q-row__title">PDF erstellen</span>
                </a>
            @endcan
            <a class="q-row" href="#">
                <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg></span>
                <span class="q-row__title">Favorisieren</span>
            </a>
            @can('delete', $memo)
                <form action="{{ route('memos.destroy', $memo) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="q-row q-row--danger">
                        <span class="q-avatar q-avatar--danger"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg></span>
                        <span class="q-row__title">Entfernen</span>
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="q-container">

        <div class="d-none d-md-block">
            @include('memo.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#voicemail"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Aktenvermerk · #{{ $memo->number }}</div>
                    <h1 class="q-title">{{ $memo->title }}</h1>
                    <div class="q-meta">
                        @if($memo->draft)
                            <span class="q-status q-status--in-progress">Entwurf</span>
                        @endif

                        <span class="q-chip">
                            <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            {{ $memo->created_at }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if($memo->draft)
                    @can('update', $memo)
                        <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('memos.publish', ['memo' => $memo, 'redirect' => 'show']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                            Veröffentlichen
                        </a>
                    @endcan
                @endif
                @can('update', $memo)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('memos.edit', $memo) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="memoShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="memoShowDropdown">
                        @can('create', \App\Models\Memo::class)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.create', ['template' => $memo]) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#files"></use></svg>
                                Kopieren
                            </a>
                        @endcan
                        @can('email', $memo)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.email', ['memo' => $memo, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
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
                            <form action="{{ route('memos.destroy', $memo) }}" method="post">
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

        {{-- Mobile-only: .q-page-head's own .q-meta (draft status + created
             chip) is hidden along with the rest of the desktop head above
             (2026-07-21, same fix as the other modules'). --}}
        <div class="q-meta d-flex d-md-none mt-2 pt-1 mb-3">
            @if($memo->draft)
                <span class="q-status q-status--in-progress">Entwurf</span>
            @endif

            <span class="q-chip">
                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                {{ $memo->created_at }}
            </span>
        </div>

        <div class="q-statbar mb-4">
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Projekt</span>
                <span class="q-statbar__value text-truncate">
                    <a href="{{ route('projects.show', $memo->project) }}">{{ $memo->project->name }}</a>
                </span>
            </div>
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Verfasser</span>
                <span class="q-statbar__value">{{ $memo->employeeComposer->person->name }}</span>
            </div>
            @if($memo->personRecipient)
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Empfänger</span>
                    <span class="q-statbar__value">{{ $memo->personRecipient->name }}</span>
                </div>
            @endif
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Datum</span>
                <span class="q-statbar__value">{{ $memo->meeting_held_on }}</span>
            </div>
            @if($memo->next_meeting_on)
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Nächster Termin</span>
                    <span class="q-statbar__value">{{ $memo->next_meeting_on }}</span>
                </div>
            @endif
        </div>

        <div class="q-detail">
            <div class="d-flex flex-column gap-3">
                {{-- Vermerk --}}
                @if ($memo->comment)
                    <div class="q-card">
                        <div class="q-card__head">Vermerk</div>
                        <div class="q-card__body">
                            <div class="markdown">
                                {!! Html::fromMarkdown($memo->comment) !!}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Anhänge --}}
                @if($memo->attachments()->count() > 0)
                    <div class="q-card">
                        <div class="q-card__head">Anhänge</div>
                        <div class="q-card__body">
                            <div class="row g-2">
                                @foreach($memo->attachments() as $attachment)
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

            {{-- Beteiligte Personen --}}
            <div class="q-card q-card--quiet q-detail__people">
                <div class="q-card__body">
                    <div class="q-aside__group">
                        <div class="q-aside__label">Anwesende Personen · {{ $memo->presentPeople->count() }}</div>
                            @forelse($memo->presentPeople as $person)
                                <div class="q-aside__person">
                                    <span class="q-avatar q-avatar--round q-avatar--sm q-avatar--{{ $person->avatar_colour }}">{{ $person->initials }}</span>
                                    <span class="q-aside__name text-truncate">{{ $person->name }}</span>
                                </div>
                            @empty
                                <div class="q-aside__person q-aside__person--muted">
                                    <span class="q-aside__name">nicht angegeben</span>
                                </div>
                            @endforelse
                        </div>

                        <div class="q-aside__group">
                            <div class="q-aside__label">Verteiler · {{ $memo->notifiedPeople->count() }}</div>
                            @forelse($memo->notifiedPeople as $person)
                                <div class="q-aside__person">
                                    <span class="q-avatar q-avatar--round q-avatar--sm q-avatar--{{ $person->avatar_colour }}">{{ $person->initials }}</span>
                                    <span class="q-aside__name text-truncate">{{ $person->name }}</span>
                                </div>
                            @empty
                                <div class="q-aside__person q-aside__person--muted">
                                    <span class="q-aside__name">nicht angegeben</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection
