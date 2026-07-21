@php
    use \App\Models\Person;
    use \App\Models\Project;
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

@if(!optional($memo)->id || optional($memo)->draft)
    <div class="q-form-section">
        <div class="q-form-section__head">
            Entwurf
            <div class="q-form-section__desc">Der Entwurfsstatus des Aktenvermerkes. Solange ein Aktenvermerk als Entwurf gekennzeichnet ist, werden die beteiligten Personen nicht benachrichtigt. Ein veröffentlichter Aktenvermerk kann nicht mehr in den Entwurfsstatus versetzt werden.</div>
        </div>
        <div class="q-form-section__body d-flex flex-column gap-3">
            @if(optional($memo)->id)
                <div class="q-banner q-banner--info">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
                    <span>Der Aktenvermerk wurde bisher als Entwurf gespeichert. Um ihn zu veröffentlichen, setze den Entwurfsstatus auf <strong>nein</strong>.</span>
                </div>
            @endif

            <div>
                <label>Entwurf</label>
                <div class="btn-group @error('draft') is-invalid @enderror">
                    <input type="radio" class="btn-check" name="draft" id="draft-1" value="1" autocomplete="off" @if(old('draft', optional($memo)->draft) == true) checked @endif>
                    <label class="btn btn-outline-secondary q-seg--amber" for="draft-1">ja</label>
                    <input type="radio" class="btn-check" name="draft" id="draft-0" value="0" autocomplete="off" @if(old('draft', optional($memo)->draft) == false) checked @endif>
                    <label class="btn btn-outline-secondary q-seg--green" for="draft-0">nein</label>
                </div>
                <div class="invalid-feedback @error('draft') d-block @enderror">
                    @error('draft')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
    </div>
@endif

<div class="q-form-section">
    <div class="q-form-section__head">
        Stammdaten
        <div class="q-form-section__desc">Die Stammdaten des Aktenvermerkes.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
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

        <div class="q-form__row q-form__row--2 q-form__row--nostack">
            <div>
                <label for="meeting_held_on">Datum</label>
                <input type="date" class="form-control @error('meeting_held_on') is-invalid @enderror" id="meeting_held_on" name="meeting_held_on" value="{{ old('meeting_held_on', optional(optional($memo)->meeting_held_on)->format('Y-m-d')) }}" />
                <div class="invalid-feedback">
                    @error('meeting_held_on')
                        {{ $message }}
                    @else
                        Gib bitte das Datum der Besprechung ein.
                    @enderror
                </div>
            </div>
            <div>
                <label for="next_meeting_on">Nächster Termin</label>
                <input type="date" class="form-control @error('next_meeting_on') is-invalid @enderror" id="next_meeting_on" name="next_meeting_on" value="{{ old('next_meeting_on', optional(optional($memo)->next_meeting_on)->format('Y-m-d')) }}" />
                <div class="invalid-feedback">
                    @error('next_meeting_on')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="project_id">Projekt</label>
            <project-dropdown :projects="{{ $projects }}" :current_project="{{ $currentProject ?? 'null' }}" v-cloak></project-dropdown>
            <div class="invalid-feedback @error('project_id') d-block @enderror">
                @error('project_id')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Beteiligte Personen
        <div class="q-form-section__desc">Verfasser und Empfänger des Aktenvermerkes sowie anwesende und notifizierte Personen.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="employee_id">Verfasser</label>
            <person-dropdown inputname="employee_id" :people="{{ $employees }}" :current_person="{{ $currentEmployeeComposer ?? 'null' }}" v-cloak></person-dropdown>
            <div class="invalid-feedback @error('employee_id') d-block @enderror">
                @error('employee_id')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="person_id">Empfänger</label>
            <person-dropdown inputname="person_id" :people="{{ $people }}" :current_person="{{ $currentPersonRecipient ?? 'null' }}" v-cloak></person-dropdown>
            <div class="invalid-feedback @error('person_id') d-block @enderror">
                @error('person_id')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Anwesende Personen</label>
            <people-selector inputname="present_ids[]" :people="{{ $people }}" :current_people="{{ $currentPresentPeople ?? 'null' }}" v-cloak></people-selector>
            <div class="invalid-feedback @error('present_ids') d-block @enderror">
                @error('present_ids')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Verteiler</label>
            <people-selector inputname="notified_ids[]" :people="{{ $people }}" :current_people="{{ $currentNotifiedPeople ?? 'null' }}" v-cloak></people-selector>
            <div class="invalid-feedback @error('notified_ids') d-block @enderror">
                @error('notified_ids')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Bemerkungen
        <div class="q-form-section__desc">Sonstige Bemerkungen zum Aktenvermerk.</div>
    </div>
    <div class="q-form-section__body">
        <markdown-editor name="comment" placeholder="Bemerkungen zum Aktenvermerk" value="{{ old('comment', optional($memo)->comment) }}" v-cloak></markdown-editor>
        <a class="q-link--quiet d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
            <svg class="icon-bs icon-16 me-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#question-circle"></use></svg>
            Hilfe zu Markdown
        </a>
        <div class="invalid-feedback @error('comment') d-block @enderror">
            @error('comment')
                {{ $message }}
            @enderror
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Anhänge
        <div class="q-form-section__desc">Dem Aktenvermerk zugeordnete Anhänge. Erlaubt sind Dateien im Bildformat oder PDF Dokumente.</div>
    </div>
    <div class="q-form-section__body">
        <attachments-selector accept="image/*, application/pdf" :current_attachments="{{ $currentAttachments ?? '[]' }}" v-cloak></attachments-selector>
        <div class="invalid-feedback @error('remove_attachments') d-block @enderror @error('remove_attachments.*') d-block @enderror @error('new_attachments') d-block @enderror @error('new_attachments.*') d-block @enderror">
            @error('remove_attachments')
                {{ $message }}
            @enderror
            @error('remove_attachments.*')
                {{ $message }}
            @enderror
            @error('new_attachments')
                {{ $message }}
            @enderror
            @error('new_attachments.*')
                {{ $message }}
            @enderror
        </div>
    </div>
</div>
