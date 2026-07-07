@extends('layouts.app')

@section('content')
    <div class="q-container">

        @include('memo.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Aktenvermerk · #{{ $memo->number }}</div>
                    <h1 class="q-title">{{ $memo->title }}</h1>
                    <div class="q-meta">
                        @if($memo->draft)
                            <span class="q-status q-status--new">Entwurf</span>
                        @endif

                        <span class="q-chip">
                            <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use></svg>
                            {{ $memo->created_at }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if($memo->draft)
                    @can('update', $memo)
                        <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('memos.publish', ['memo' => $memo, 'redirect' => 'show']) }}">
                            <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check"></use></svg>
                            Veröffentlichen
                        </a>
                    @endcan
                @endif
                @can('update', $memo)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('memos.edit', $memo) }}">
                        <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="memoShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="memoShowDropdown">
                        @can('create', \App\Models\Memo::class)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.create', ['template' => $memo]) }}">
                                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#copy"></use></svg>
                                Kopieren
                            </a>
                        @endcan
                        @can('email', $memo)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.email', ['memo' => $memo, 'redirect' => 'show']) }}">
                                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $memo)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.download', $memo) }}" target="_blank">
                                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $memo)
                            <form action="{{ route('memos.destroy', $memo) }}" method="post">
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
                                                    <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use></svg>
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

            <div class="d-flex flex-column gap-3">
                {{-- Beteiligte Personen --}}
                <div class="q-card q-card--quiet">
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
    </div>
@endsection
