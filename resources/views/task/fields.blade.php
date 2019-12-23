@php
    use \App\Person;
    use \App\Project;
@endphp

@if (old('project_id'))
    @php $currentProject = Project::find(old('project_id')); @endphp
@endif

@if (old('employee_id'))
    @php $currentResponsibleEmployee = Person::find(old('employee_id')); @endphp
@endif

@if (old('involved_ids'))
    @php $currentInvolvedEmployees = Person::order()->find(old('involved_ids'))->toJson(); @endphp
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
            Die Stammdaten der Aufgabe.
        </p>
        <p class="text-muted">
            Bei Setzten des Status auf neu oder in Barbeitung wird das Enddatum entfernt, falls dieses gesetzt ist.
        </p>
        <p class="text-muted">
            Bei Markierung der Aufgabe als erledigt werden das Start- bzw. Enddatum automatisch auf das aktuelle Datum gesetzt, falls diese nicht manuell angegeben wurden.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
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

        <div class="form-group">
            <label for="name_2">Startdatum</label>
            <input type="date" class="form-control @error('starts_on') is-invalid @enderror" id="starts_on" name="starts_on" placeholder="" value="{{ old('starts_on', optional(optional($task)->starts_on)->format('Y-m-d')) }}" />
            <div class="invalid-feedback">
                @error('starts_on')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="name_2">Fälligkeitsdatum</label>
            <input type="date" class="form-control @error('due_on') is-invalid @enderror" id="due_on" name="due_on" placeholder="" value="{{ old('due_on', optional(optional($task)->due_on)->format('Y-m-d')) }}" />
            <div class="invalid-feedback">
                @error('due_on')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="name_2">Enddatum</label>
            <input type="date" class="form-control @error('ends_on') is-invalid @enderror" id="ends_on" name="ends_on" placeholder="" value="{{ old('ends_on', optional(optional($task)->ends_on)->format('Y-m-d')) }}" />
            <div class="invalid-feedback">
                @error('ends_on')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="gender">Priorität</label>
            </div>
            <div class="btn-group btn-group-toggle @error('priority') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('priority', optional($task)->priority) == 'low') active @endif">
                    <input type="radio" name="priority" id="low" value="low" autocomplete="off" @if(old('priority', optional($task)->priority) == 'low') checked @endif> niedrig
                </label>
                <label class="btn btn-outline-secondary @if(old('priority', optional($task)->priority) == 'medium') active @endif">
                    <input type="radio" name="priority" id="medium" value="medium" autocomplete="off" @if(old('priority', optional($task)->priority) == 'medium') checked @endif> mittel
                </label>
                <label class="btn btn-outline-secondary @if(old('priority', optional($task)->priority) == 'high') active @endif">
                    <input type="radio" name="priority" id="high" value="high" autocomplete="off" @if(old('priority', optional($task)->priority) == 'high') checked @endif> hoch
                </label>
            </div>
            <div class="invalid-feedback @error('priority') d-block @enderror">
                @error('priority')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="gender">Status</label>
            </div>
            <div class="btn-group btn-group-toggle @error('status') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('status', optional($task)->status) == 'new') active @endif">
                    <input type="radio" name="status" id="new" value="new" autocomplete="off" @if(old('status', optional($task)->status) == 'new') checked @endif> neu
                </label>
                <label class="btn btn-outline-secondary @if(old('status', optional($task)->status) == 'in progress') active @endif">
                    <input type="radio" name="status" id="in progress" value="in progress" autocomplete="off" @if(old('status', optional($task)->status) == 'in progress') checked @endif> in Bearbeitung
                </label>
                <label class="btn btn-outline-secondary @if(old('status', optional($task)->status) == 'finished') active @endif">
                    <input type="radio" name="status" id="finished" value="finished" autocomplete="off" @if(old('status', optional($task)->status) == 'finished') checked @endif> erledigt
                </label>
            </div>
            <div class="invalid-feedback @error('status') d-block @enderror">
                @error('status')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="gender">Verrechnungsstatus</label>
            </div>
            <div class="btn-group btn-group-toggle @error('billed') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('billed', optional($task)->billed) == 'yes') active @endif">
                    <input type="radio" name="billed" id="yes" value="yes" autocomplete="off" @if(old('billed', optional($task)->billed) == 'yes') checked @endif> verrechnet
                </label>
                <label class="btn btn-outline-secondary @if(old('billed', optional($task)->billed) == 'no') active @endif">
                    <input type="radio" name="billed" id="no" value="no" autocomplete="off" @if(old('billed', optional($task)->billed) == 'no') checked @endif> nicht verrechnet
                </label>
                <label class="btn btn-outline-secondary @if(old('billed', optional($task)->billed) == 'warranty') active @endif">
                    <input type="radio" name="billed" id="warranty" value="warranty" autocomplete="off" @if(old('billed', optional($task)->billed) == 'warranty') checked @endif> Garantie
                </label>
            </div>
            <div class="invalid-feedback @error('billed') d-block @enderror">
                @error('billed')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="company_id">Projekt</label>
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
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#lock"></use>
            </svg>
            Sichtbarkeitsstatus
        </p>
        <p class="text-muted">
            Eine private Aufgabe kann nur vom verantwortlichen Mitarbeiter sowie weiteren beteiligten Mitarbeitern eingesehen, bearbeitet oder kommentiert werden.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div>
                <label for="gender">Sichtbarkeitsstatus</label>
            </div>
            <div class="btn-group btn-group-toggle @error('private') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('private', optional($task)->private) == '0') active @endif">
                    <input type="radio" name="private" id="0" value="0" autocomplete="off" @if(old('private', optional($task)->private) == '0') checked @endif> öffentlich
                </label>
                <label class="btn btn-outline-secondary @if(old('private', optional($task)->private) == '1') active @endif">
                    <input type="radio" name="private" id="1" value="1" autocomplete="off" @if(old('private', optional($task)->private) == '1') checked @endif> privat
                </label>
            </div>
            <div class="invalid-feedback @error('private') d-block @enderror">
                @error('private')
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
            Beteiligte Mitarbeiter
        </p>
        <p class="text-muted">
            Mitarbeiter, die aktiv an der Aufgabe mitwirken.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="employee_id">Verantwortlicher Mitarbeiter</label>
            <person-dropdown inputname="employee_id" :people="{{ $employees }}" :current_person="{{ $currentResponsibleEmployee ?? 'null' }}"></person-dropdown>
            <div class="invalid-feedback @error('employee_id') d-block @enderror">
                @error('employee_id')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="involved_ids">Weitere beteiligte Mitarbeiter</label>
            <people-selector inputname="involved_ids[]" :people="{{ $employees }}" :current_people="{{ $currentInvolvedEmployees ?? 'null' }}"></people-selector>
            <div class="invalid-feedback @error('involved_ids') d-block @enderror">
                @error('involved_ids')
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
            Sonstige Bemerkungen zur Aufgabe.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="comment">
                Bemerkungen
            </label>
            <vue-easymde :configs="{spellChecker: false, status: false, showIcons: ['strikethrough', 'table', ], hideIcons: ['guide', ] }" name="comment" placeholder="Bemerkungen zur Firma"  value="{{ old('comment', optional($task)->comment) }}"></vue-easymde>
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
