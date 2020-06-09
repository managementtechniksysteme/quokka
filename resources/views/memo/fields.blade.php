@php
    use \App\Person;
    use \App\Project;
@endphp

@if (old('project_id'))
    @php $currentProject = Project::find(old('project_id')); @endphp
@endif

@if (old('employee_id'))
    @php $currentEmployeeComposer = Person::find(old('employee_id')); @endphp
@endif

@if (old('person_id'))
    @php $currentPersonRecipient = Person::find(old('person_id')); @endphp
@endif

@if (old('present_ids'))
    @php $currentPresentPeople = Person::order()->find(old('present_ids'))->toJson(); @endphp
@endif

@if (old('notified_ids'))
    @php $currentNotifiedPeople = Person::order()->find(old('notified_ids'))->toJson(); @endphp
@endif

@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
            </svg>
            Stammdaten
        </p>
        <p class="text-muted">
            Die Stammdaten des Aktenvermerkes.
        </p>
        <p class="text-muted">
            Das Verfassungsdatum des Aktenvermerkes wird automatisch auf das aktuelle Datum gesetzt.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="title">Titel</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Musteraktenvermerk" value="{{ old('title', optional($memo)->title) }}" required />
            <div class="invalid-feedback">
                @error('title')
                    {{ $message }}
                @else
                    Gib bitte den Titel des Aktenvermerkes ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="meeting_held_on">Datum</label>
            <input type="date" class="form-control @error('meeting_held_on') is-invalid @enderror" id="meeting_held_on" name="meeting_held_on" placeholder="" value="{{ old('meeting_held_on', optional(optional($memo)->meeting_held_on)->format('Y-m-d')) }}" />
            <div class="invalid-feedback">
                @error('meeting_held_on')
                    {{ $message }}
                @else
                    Gib bitte das Datum der Besprechung ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="next_meeting_on">Nächster Termin</label>
            <input type="date" class="form-control @error('next_meeting_on') is-invalid @enderror" id="next_meeting_on" name="next_meeting_on" placeholder="" value="{{ old('next_meeting_on', optional(optional($memo)->next_meeting_on)->format('Y-m-d')) }}" />
            <div class="invalid-feedback">
                @error('next_meeting_on')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="project_id">Projekt</label>
            <project-dropdown :projects="{{ $projects }}" :current_project="{{ $currentProject ?? 'null' }}"></project-dropdown>
            <div class="invalid-feedback @error('project_id') d-block @enderror">
                @error('project_id')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
            </svg>
            Beteiligte Personen
        </p>
        <p class="text-muted">
            Verfasser und Empfänger des Aktenvermerkes. Personen, die bei der Besprechung anwesend waren und Personen, an welche der Aktenvermerk verteilt wird.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="employee_id">Verfasser</label>
            <person-dropdown inputname="employee_id" :people="{{ $employees }}" :current_person="{{ $currentEmployeeComposer ?? 'null' }}"></person-dropdown>
            <div class="invalid-feedback @error('employee_id') d-block @enderror">
                @error('employee_id')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="person_id">Empfänger</label>
            <person-dropdown inputname="person_id" :people="{{ $people }}" :current_person="{{ $currentPersonRecipient ?? 'null' }}"></person-dropdown>
            <div class="invalid-feedback @error('person_id') d-block @enderror">
                @error('person_id')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="present_ids">Anwesende Personen</label>
            <people-selector inputname="present_ids[]" :people="{{ $people }}" :current_people="{{ $currentPresentPeople ?? 'null' }}"></people-selector>
            <div class="invalid-feedback @error('present_ids') d-block @enderror">
                @error('present_ids')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="notified_ids">Verteiler</label>
            <people-selector inputname="notified_ids[]" :people="{{ $people }}" :current_people="{{ $currentNotifiedPeople ?? 'null' }}"></people-selector>
            <div class="invalid-feedback @error('notified_ids') d-block @enderror">
                @error('notified_ids')
                    {{ $message }}
                @enderror
            </div>
        </div>

    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Bemerkungen
        </p>
        <p class="text-muted">
            Sonstige Bemerkungen zum Aktenvermerk.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="comment">
                Bemerkungen
            </label>
            <vue-easymde :configs="{spellChecker: false, status: false, showIcons: ['strikethrough', 'table', ], hideIcons: ['guide', ] }" name="comment" placeholder="Bemerkungen zum Aktenvermerk"  value="{{ old('comment', optional($memo)->comment) }}"></vue-easymde>
            <a class="text-muted d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
                <svg class="feather feather-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#help-circle"></use>
                </svg>
                Hilfe zu Markdown
            </a>
            <div class="invalid-feedback @error('comment') d-block @enderror">
                @error('comment')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>
