@php
    use \App\Models\Project;
@endphp

@if (old('project_id'))
    @php $currentProject = Project::find(old('project_id')); @endphp
@endif

@if (old('services'))
    @php $currentServices = json_encode(old('services')); @endphp
@endif

@csrf

@unless(Auth::user()->signature())
    <div class="q-banner">
        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
        <span>Du hast noch keine Unterschrift im System hinterlegt. Es kann nicht automatisch eine Unterschrift in PDF Ausdrucke von Berichten eingefügt werden. Füge bitte eine Unterschrift in den <a href="{{ route('user-settings.edit', ['tab' => 'general']) }}">allgemeinen Einstellungen</a> hinzu.</span>
    </div>
@endunless

<div class="q-form-section">
    <div class="q-form-section__head">
        Stammdaten
        <div class="q-form-section__desc">Die Stammdaten des Serviceberichtes. Bei der Bearbeitung eines bereits unterschriebenen Serviceberichtes wird die vorhandene Unterschrift entfernt.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="employee">Techniker</label>
            <input type="text" class="form-control" name="employee" id="employee" placeholder="{{ optional($serviceReport)->employee->person->name ?? Auth::user()->person->name }}" disabled />
        </div>

        <div>
            <label>Status</label>
            @if(optional($serviceReport)->status === 'signed')
                <div class="q-banner">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
                    <span>Der Servicebericht wurde bereits unterschrieben. Beim Speichern wird die aktuelle Unterschrift entfernt! Eine erneute Anfrage zum Unterschreiben kann gesendet werden.</span>
                </div>
            @endif
            <div class="btn-group">
                <input type="radio" class="btn-check" name="status" id="status-new" value="new" @if(optional($serviceReport)->status == 'new' || !$serviceReport) checked @endif disabled>
                <label class="btn btn-outline-secondary q-seg--sky" for="status-new">neu</label>
                <input type="radio" class="btn-check" name="status" id="status-signed" value="signed" @if(optional($serviceReport)->status == 'signed') checked @endif disabled>
                <label class="btn btn-outline-secondary q-seg--amber" for="status-signed">unterschrieben</label>
                <input type="radio" class="btn-check" name="status" id="status-finished" value="finished" @if(optional($serviceReport)->status == 'finished') checked @endif disabled>
                <label class="btn btn-outline-secondary q-seg--green" for="status-finished">erledigt</label>
            </div>
        </div>

        <div>
            <label for="project_id">Projekt</label>
            <project-dropdown :projects="{{ $projects }}" :current_project="{{ $currentProject ?? 'null' }}" change_event="onservicereportprojectchange"></project-dropdown>
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
        Serviceleistungen
        <div class="q-form-section__desc">Serviceleistungen werden automatisch nach Datum gruppiert und Werte entsprechend summiert.</div>
    </div>
    <div class="q-form-section__body">
        <services-selector :current_services="{{ $currentServices ?? 'null' }}" :current_report_id="{{ $serviceReport->id ?? 'null' }}" v-cloak></services-selector>
        <div class="invalid-feedback @error('services') d-block @enderror @error('services.*') d-block @enderror">
            @error('services')
                {{ $message }}
            @enderror
            @error('services.*')
                {{ $message }}
            @enderror
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Bemerkungen
        <div class="q-form-section__desc">Sonstige Bemerkungen zum Servicebericht.</div>
    </div>
    <div class="q-form-section__body">
        <markdown-editor name="comment" placeholder="Bemerkungen zum Servicebericht" value="{{ old('comment', optional($serviceReport)->comment) }}" v-cloak></markdown-editor>
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
        <div class="q-form-section__desc">Dem Servicebericht zugeordnete Anhänge. Erlaubt sind Dateien im Bildformat oder PDF Dokumente.</div>
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

<div class="q-form-section">
    <div class="q-form-section__head">
        Anfrage zur Unterschrift senden
        <div class="q-form-section__desc">Bei Aktivierung kann nach dem Speichern direkt eine Anfrage zur Unterschrift per Email versendet werden.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div class="q-banner q-banner--info">
            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
            <span>Die Email Adresse kann im nächsten Schritt angegeben werden.</span>
        </div>
        <div>
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input @error('send_signature_request') is-invalid @enderror" name="send_signature_request" id="send_signature_request" value="true">
                <label class="form-check-label" for="send_signature_request">Anfrage zur Unterschrift nach dem Speichern senden.</label>
            </div>
            <div class="invalid-feedback @error('send_signature_request') d-block @enderror">
                @error('send_signature_request')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>
