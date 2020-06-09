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
            {{ $memo->project->name }}
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
                    {{ $memo->meeting_held_on->format('d.m.Y') }}
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
                    {{ $memo->next_meeting_on ? $memo->next_meeting_on->format('d.m.Y') : 'nicht angegeben' }}
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
                    {{ $memo->created_at->format('d.m.Y') }}
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

    <div class="text-muted d-flex align-items-center mt-4">
        <svg class="feather feather-16 mr-2">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
        </svg>
        Bemerkungen
    </div>
    @if ($memo->comment)
        @markdown ($memo->comment)
    @else
        keine Bemerkungen angegeben
    @endif

</div>

