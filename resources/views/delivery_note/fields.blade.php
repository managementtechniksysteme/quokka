@php
    use \App\Models\Project;
@endphp

@if (old('project_id'))
    @php $currentProject = Project::find(old('project_id')); @endphp
@endif

@csrf

{{-- Stammdaten --}}
<div class="q-form-section">
    <div class="q-form-section__head">
        Stammdaten
        <div class="q-form-section__desc">
            Die Stammdaten des Lieferscheins. Bei der Bearbeitung eines bereits unterschriebenen Lieferscheins wird die vorhandene Unterschrift entfernt.
        </div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="employee">Mitarbeiter</label>
            <input type="text" class="form-control" name="employee" id="employee" placeholder="{{ optional($deliveryNote)->employee->person->name ?? Auth::user()->person->name }}" disabled />
        </div>

        <div class="q-form__row q-form__row--2">
            <div>
                <label for="written_on">Datum</label>
                <input type="date" class="form-control @error('written_on') is-invalid @enderror" id="written_on"
                       name="written_on" placeholder=""
                       value="{{ old('written_on', optional(optional($deliveryNote)->written_on)->format('Y-m-d')) }}"
                       required/>
                <div class="invalid-feedback">
                    @error('written_on')
                        {{ $message }}
                    @else
                        Gib bitte das Datum des Lieferscheins ein.
                    @enderror
                </div>
            </div>

            <div>
                <label for="title">Lieferscheinnummer (Titel)</label>
                <div class="input-group has-validation">
                    <span class="input-group-text">LI-</span>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="101595/2023" value="{{ old('title', optional($deliveryNote)->title) }}" required />
                    <div class="invalid-feedback">
                        @error('title')
                            {{ $message }}
                        @else
                            Gib bitte die Nummer des Lieferscheins ein.
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label for="status">Status</label>
            @if(optional($deliveryNote)->status === 'signed')
                <div class="q-banner mb-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
                    <span>Der Lieferschein wurde bereits unterschrieben. Beim Speichern wird die aktuelle Unterschrift entfernt! Eine erneute Anfrage zum Unterschreiben kann gesendet werden.</span>
                </div>
            @endif
            <div class="btn-group">
                <input type="radio" class="btn-check" name="status" id="status-new" value="new" @if(optional($deliveryNote)->status == 'new' || !$deliveryNote) checked @endif disabled>
                <label class="btn btn-outline-secondary" for="status-new">neu</label>
                <input type="radio" class="btn-check" name="status" id="status-signed" value="signed" @if(optional($deliveryNote)->status == 'signed') checked @endif disabled>
                <label class="btn btn-outline-secondary" for="status-signed">unterschrieben</label>
                <input type="radio" class="btn-check" name="status" id="status-finished" value="finished" @if(optional($deliveryNote)->status == 'finished') checked @endif disabled>
                <label class="btn btn-outline-secondary" for="status-finished">erledigt</label>
            </div>
        </div>

        <div>
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

{{-- Bemerkungen --}}
<div class="q-form-section">
    <div class="q-form-section__head">
        Bemerkungen
        <div class="q-form-section__desc">
            Sonstige Bemerkungen zum Lieferschein. Diese sind auch dem Kunden bei Interaktion einsehbar!
        </div>
    </div>
    <div class="q-form-section__body">
        <markdown-editor name="comment" placeholder="Bemerkungen zum Lieferschein" value="{{ old('comment', optional($deliveryNote)->comment) }}" v-cloak></markdown-editor>
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

{{-- Lieferschein (PDF) --}}
<div class="q-form-section">
    <div class="q-form-section__head">
        Lieferschein
        <div class="q-form-section__desc">Der Lieferschein im PDF Format.</div>
    </div>
    <div class="q-form-section__body">
        <label for="document">Lieferschein im PDF Format{{ $deliveryNote ? ' (Ohne Auswahl wird der aktuelle Lieferschein beibehalten)' : '' }}</label>
        <input type="file" accept="application/pdf" class="form-control" id="document" name="document">
        <div class="invalid-feedback @error('document') d-block @enderror">
            @error('document')
                {{ $message }}
            @else
                Wähle bitte das PDF Dokument des Lieferscheins aus.
            @enderror
        </div>
    </div>
</div>

{{-- Anfrage zur Unterschrift senden --}}
<div class="q-form-section">
    <div class="q-form-section__head">
        Anfrage zur Unterschrift senden
        <div class="q-form-section__desc">
            Bei Aktivierung der Schaltfläche kann nach dem Speichern direkt eine Anfrage zur Unterschrift per Email versendet werden.
        </div>
    </div>
    <div class="q-form-section__body">
        <div class="q-banner q-banner--info">
            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
            <span>Die Email Adresse kann im nächsten Schritt angegeben werden.</span>
        </div>
        <div class="form-check form-switch">
            <input type="checkbox" class="form-check-input @error('send_signature_request') is-invalid @enderror" name="send_signature_request" id="send_signature_request" value="true">
            <label class="form-check-label" for="send_signature_request">Anfrage zur Unterschrift nach dem Speichern senden.</label>
            <div class="invalid-feedback @error('send_signature_request') d-block @enderror">
                @error('send_signature_request')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>
