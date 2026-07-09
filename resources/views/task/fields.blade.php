@php
    use \App\Models\Person;
    use \App\Models\Project;
@endphp

@if (old('project_id'))
    @php $currentProject = Project::find(old('project_id')); @endphp
@endif

@if (old('employee_id'))
    @php $currentResponsibleEmployee = Person::with('employee.user')->find(old('employee_id')); @endphp
@endif

@if (old('involved_ids'))
    @php $currentInvolvedEmployees = Person::order()->with('employee.user')->find(old('involved_ids'))->toJson(); @endphp
@endif

@csrf

{{-- Stammdaten --}}
<div class="q-form-section">
    <div class="q-form-section__head">
        Stammdaten
        <div class="q-form-section__desc">
            Status „neu“ oder „in Arbeit“ entfernt ein gesetztes Enddatum. „Erledigt“ setzt Start- und Enddatum automatisch auf heute, falls nicht angegeben.
        </div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="name">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Musteraufgabe" value="{{ old('name', optional($task)->name) }}" required />
            <div class="invalid-feedback">
                @error('name')
                    {{ $message }}
                @else
                    Gib bitte den Namen der Aufgabe ein.
                @enderror
            </div>
        </div>

        <div class="q-form__row q-form__row--3">
            <div>
                <label for="starts_on">Startdatum</label>
                <input type="date" class="form-control @error('starts_on') is-invalid @enderror" id="starts_on" name="starts_on" placeholder="" value="{{ old('starts_on', optional(optional($task)->starts_on)->format('Y-m-d')) }}" />
                <div class="invalid-feedback">
                    @error('starts_on')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div>
                <label for="due_on">Fälligkeitsdatum</label>
                <input type="date" class="form-control @error('due_on') is-invalid @enderror" id="due_on" name="due_on" placeholder="" value="{{ old('due_on', optional(optional($task)->due_on)->format('Y-m-d')) }}" />
                <div class="invalid-feedback">
                    @error('due_on')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div>
                <label for="ends_on">Enddatum</label>
                <input type="date" class="form-control @error('ends_on') is-invalid @enderror" id="ends_on" name="ends_on" placeholder="" value="{{ old('ends_on', optional(optional($task)->ends_on)->format('Y-m-d')) }}" />
                <div class="invalid-feedback">
                    @error('ends_on')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="q-form__row q-form__row--3">
            <div>
                <label for="priority">Priorität</label>
                <div class="btn-group @error('priority') is-invalid @enderror">
                    <input type="radio" class="btn-check" name="priority" id="priority-low" value="low" autocomplete="off" @if(old('priority', optional($task)->priority) == 'low') checked @endif>
                    <label class="btn btn-outline-secondary q-seg--green" for="priority-low">niedrig</label>
                    <input type="radio" class="btn-check" name="priority" id="priority-medium" value="medium" autocomplete="off" @if(old('priority', optional($task)->priority) == 'medium') checked @endif>
                    <label class="btn btn-outline-secondary q-seg--amber" for="priority-medium">mittel</label>
                    <input type="radio" class="btn-check" name="priority" id="priority-high" value="high" autocomplete="off" @if(old('priority', optional($task)->priority) == 'high') checked @endif>
                    <label class="btn btn-outline-secondary q-seg--red" for="priority-high">hoch</label>
                </div>
                <div class="invalid-feedback @error('priority') d-block @enderror">
                    @error('priority')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div>
                <label for="status">Status</label>
                <div class="btn-group @error('status') is-invalid @enderror">
                    <input type="radio" class="btn-check" name="status" id="status-new" value="new" autocomplete="off" @if(old('status', optional($task)->status) == 'new') checked @endif>
                    <label class="btn btn-outline-secondary q-seg--sky" for="status-new">neu</label>
                    <input type="radio" class="btn-check" name="status" id="status-in-progress" value="in progress" autocomplete="off" @if(old('status', optional($task)->status) == 'in progress') checked @endif>
                    <label class="btn btn-outline-secondary q-seg--amber" for="status-in-progress">in Arbeit</label>
                    <input type="radio" class="btn-check" name="status" id="status-finished" value="finished" autocomplete="off" @if(old('status', optional($task)->status) == 'finished') checked @endif>
                    <label class="btn btn-outline-secondary q-seg--green" for="status-finished">erledigt</label>
                </div>
                <div class="invalid-feedback @error('status') d-block @enderror">
                    @error('status')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div>
                <label for="billed">Verrechnungsstatus</label>
                <div class="btn-group @error('billed') is-invalid @enderror">
                    <input type="radio" class="btn-check" name="billed" id="billed-yes" value="yes" autocomplete="off" @if(old('billed', optional($task)->billed) == 'yes') checked @endif>
                    <label class="btn btn-outline-secondary" for="billed-yes">verrechnet</label>
                    <input type="radio" class="btn-check" name="billed" id="billed-no" value="no" autocomplete="off" @if(old('billed', optional($task)->billed) == 'no') checked @endif>
                    <label class="btn btn-outline-secondary" for="billed-no">nicht verrechnet</label>
                    <input type="radio" class="btn-check" name="billed" id="billed-warranty" value="warranty" autocomplete="off" @if(old('billed', optional($task)->billed) == 'warranty') checked @endif>
                    <label class="btn btn-outline-secondary" for="billed-warranty">Garantie</label>
                </div>
                <div class="invalid-feedback @error('billed') d-block @enderror">
                    @error('billed')
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

{{-- Sichtbarkeit --}}
<div class="q-form-section">
    <div class="q-form-section__head">
        Sichtbarkeit
        <div class="q-form-section__desc">
            Eine private Aufgabe kann nur vom verantwortlichen sowie weiteren beteiligten Mitarbeitern eingesehen, bearbeitet oder kommentiert werden, falls entsprechende Berechtigungen zugewiesen wurden.
        </div>
    </div>
    <div class="q-form-section__body">
        <label for="private">Sichtbarkeitsstatus</label>
        <div class="btn-group @error('private') is-invalid @enderror">
            @can('tasks.create')
                <input type="radio" class="btn-check" name="private" id="private-0" value="0" autocomplete="off" @if(old('private', optional($task)->private) == '0' || auth()->user()->cannot('tasks.create.private')) checked @endif>
                <label class="btn btn-outline-secondary" for="private-0">öffentlich</label>
            @endcan
            @can('tasks.create.private')
                <input type="radio" class="btn-check" name="private" id="private-1" value="1" autocomplete="off" @if(old('private', optional($task)->private) == '1' || auth()->user()->cannot('tasks.create')) checked @endif>
                <label class="btn btn-outline-secondary" for="private-1">privat</label>
            @endcan
        </div>
        <div class="invalid-feedback @error('private') d-block @enderror">
            @error('private')
                {{ $message }}
            @enderror
        </div>
    </div>
</div>

{{-- Beteiligte Mitarbeiter --}}
<div class="q-form-section">
    <div class="q-form-section__head">
        Beteiligte Mitarbeiter
        <div class="q-form-section__desc">Mitarbeiter, die aktiv an der Aufgabe mitwirken.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="employee_id">Verantwortlicher Mitarbeiter</label>
            <person-dropdown inputname="employee_id" :people="{{ $employees }}" :current_person="{{ $currentResponsibleEmployee ?? 'null' }}" v-cloak></person-dropdown>
            <div class="invalid-feedback @error('employee_id') d-block @enderror">
                @error('employee_id')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="involved_ids">Weitere beteiligte Mitarbeiter</label>
            <people-selector inputname="involved_ids[]" :people="{{ $employees }}" :current_people="{{ $currentInvolvedEmployees ?? 'null' }}" v-cloak></people-selector>
            <div class="invalid-feedback @error('involved_ids') d-block @enderror">
                @error('involved_ids')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

{{-- Bemerkungen --}}
<div class="q-form-section">
    <div class="q-form-section__head">
        Bemerkungen
        <div class="q-form-section__desc">Sonstige Bemerkungen zur Aufgabe.</div>
    </div>
    <div class="q-form-section__body">
        <markdown-editor name="comment" placeholder="Bemerkungen zur Aufgabe" value="{{ old('comment', optional($task)->comment) }}" v-cloak></markdown-editor>
        <a class="text-muted d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
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

{{-- Anhänge --}}
<div class="q-form-section">
    <div class="q-form-section__head">
        Anhänge
        <div class="q-form-section__desc">
            Der Aufgabe zugeordnete Anhänge (Bildformate oder PDF). Der Dateiname neu hinzugefügter Anhänge kann durch Markieren und Überschreiben geändert werden.
        </div>
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
