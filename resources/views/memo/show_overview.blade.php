<div class="mt-4">

    <div class="row">
        <div class="col-sm-5 col-md-3 col-lg-2">
            <div class="text-muted d-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                </svg>
                Projekt
            </div>
        </div>
        <div class="col">
            <a href="{{ route('projects.show', $memo->project) }}">{{ $memo->project->name }}</a>
        </div>
    </div>

    <div class="row mt-3 mt-md-4">
        <div class="col-md">
            <div class="row">
                <div class="col-sm-5 col-md col-lg-4">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#hash"></use>
                        </svg>
                        Nummer
                    </div>
                </div>
                <div class="col col-sm-7 col-md col-lg-8">
                    {{ $memo->number }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-5 col-md col-lg-4">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                        </svg>
                        Verfasser
                    </div>
                </div>
                <div class="col col-sm-7 col-md col-lg-8">
                    {{ $memo->employeeComposer->person->name }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-5 col-md col-lg-4">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                        </svg>
                        Empfänger
                    </div>
                </div>
                <div class="col col-sm-7 col-md col-lg-8">
                    {{ optional($memo->personRecipient)->name ?? 'nicht angegeben' }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-5 col-md col-lg-4">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                        </svg>
                        Datum
                    </div>
                </div>
                <div class="col col-sm-7 col-md col-lg-8">
                    {{ $memo->meeting_held_on }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-5 col-md col-lg-4">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                        </svg>
                        Nächster Termin
                    </div>
                </div>
                <div class="col col-sm-7 col-md col-lg-8">
                    {{ $memo->next_meeting_on ? $memo->next_meeting_on : 'nicht angegeben' }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-5 col-md col-lg-4">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                        </svg>
                        Verfassungsdatum
                    </div>
                </div>
                <div class="col col-sm-7 col-md col-lg-8">
                    {{ $memo->created_at }}
                </div>
            </div>
        </div>

        <div class="col-md">
            <div class="row mt-3 mt-md-0">
                <div class="col-sm-5 col-md-12 col-lg-5">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                        </svg>
                        Anwesende Personen
                    </div>
                </div>
                <div class="col col-sm-7 col-md-12 col-lg-7">
                    @forelse($memo->presentPeople as $person)
                        {{ $person->name }}
                        @unless($loop->last)
                            <br />
                        @endunless
                    @empty
                        keine Personen angegeben
                    @endforelse
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-5 col-md-12 col-lg-5">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                        </svg>
                        Verteiler
                    </div>
                </div>
                <div class="col col-sm-7 col-md-12 col-lg-7">
                    @forelse($memo->notifiedPeople as $person)
                        {{ $person->name }}
                        @unless($loop->last)
                            <br />
                        @endunless
                    @empty
                        keine Personen angegeben
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @if ($memo->comment)
    <div class="text-muted d-flex align-items-center mt-4">
        <svg class="feather feather-16 mr-2">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
        </svg>
        Bemerkungen
    </div>
        @markdown ($memo->comment)
    @endif

    @if($memo->attachments()->count() > 0)
        <div class="row text-muted d-flex align-items-center mt-1">
            <div class="col">
                <div class="d-none d-md-inline-flex align-items-center">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                    </svg>
                    Anhänge
                </div>
                <a class="d-inline-flex d-md-none d-inline-flex align-items-center" data-toggle="collapse" href="#collapseMemoAttachments-{{ $memo->id }}" role="button" aria-expanded="false" aria-controls="collapseMemoAttachments-{{ $memo->id }}">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                    </svg>
                    Anhänge
                </a>
            </div>
        </div>
        <div class="d-none d-md-block">
            <div class="row">
                @foreach($memo->attachments() as $attachment)
                    <div class="col-12 col-md-6 col-lg-3 mt-1">
                        <div class="attachment bg-gray-100 border border-gray-300 d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                            <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
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
        <div class="collapse d-md-none" id="collapseMemoAttachments-{{ $memo->id }}">
            <div class="row">
                @foreach($memo->attachments() as $attachment)
                    <div class="col-12 col-md-6 col-lg-3 mt-1">
                        <div class="attachment bg-gray-100 border border-gray-300 d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                            <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
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

