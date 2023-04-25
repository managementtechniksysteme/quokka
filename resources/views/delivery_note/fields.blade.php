@php
    use \App\Models\Project;
@endphp

@if (old('project_id'))
    @php $currentProject = Project::find(old('project_id')); @endphp
@endif

@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
            </svg>
            Stammdaten
        </p>
        <p class="text-muted">
            Die Stammdaten des Lieferscheins.
        </p>
        <p class="text-muted">
            Bei der Bearbeitung eines bereits unterschriebenen Lieferscheins wird die vorhandene Unterschrift entferent.
            In diesem Fall muss auch eine neue PDF Datei hochgeladen werden.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="employee">Mitarbeiter</label>
            <input type="text" class="form-control" name="employee" id="employee" placeholder="{{ optional($deliveryNote)->employee->person->name ?? Auth::user()->person->name }}" disabled />
        </div>

        <div class="form-group">
            <label for="written_on">Datum</label>
            <input type="date" class="form-control @error('inspected_on') is-invalid @enderror" id="written_on"
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

        <div class="form-group">
            <label for="title">Lieferscheinnummer (Titel)</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">LI-</span>
                </div>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="101595/2023" value="{{ old('title', optional($deliveryNote)->title) }}" required />
            </div>
            <div class="invalid-feedback">
                @error('title')
                {{ $message }}
                @else
                    Gib bitte die Nummer des Lieferscheins ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="status">Status</label>
            </div>
            @if(optional($deliveryNote)->status === 'signed')
                <div class="alert alert-warning mt-1" role="alert">
                    <div class="d-inline-flex align-items-center">
                        <svg class="icon icon-24 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                        </svg>
                        Der Lieferschein wurde bereits unterschrieben. Beim Speichern wird die aktuelle Unterschrift entfernt! Eine erneute Anfrage zum Unterschreiben kann gesendet werden.
                        Eine neue PDF Datei muss ebenfalls hochgeladen werden.
                    </div>
                </div>
            @endif
            <div class="btn-group btn-group-toggle">
                <label class="btn btn-outline-secondary @if(optional($deliveryNote)->status == 'new' || !$deliveryNote) active @else disabled @endif">
                    <input type="radio" name="status" id="new" @if(optional($deliveryNote)->status == 'new' || !$deliveryNote) checked @endif disabled> neu
                </label>
                <label class="btn btn-outline-secondary @if(optional($deliveryNote)->status == 'signed') active @else disabled  @endif">
                    <input type="radio" name="status" id="signed" @if(optional($deliveryNote)->status == 'signed') checked @endif disabled> unterschrieben
                </label>
                <label class="btn btn-outline-secondary @if(optional($deliveryNote)->status == 'finished') active @else disabled  @endif">
                    <input type="radio" name="status" id="finished" @if(optional($deliveryNote)->status == 'finished') checked @endif disabled> erledigt
                </label>
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
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Bemerkungen
        </p>
        <p class="text-muted">
            Sonstige Bemerkungen zum Lieferschein. Diese sind auch dem Kunden bei Interaktion einsehbar!
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="comment">
                Bemerkungen
            </label>
            <markdown-editor name="comment" placeholder="Bemerkungen zum Lieferschein"  value="{{ old('comment', optional($deliveryNote)->comment) }}" v-cloak></markdown-editor>
            <a class="text-muted d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
                <svg class="icon icon-16 mr-1">
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

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
            </svg>
            Lieferschein
        </p>
        <p class="text-muted">
            Der Lieferschein im PDF Format.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label>Lieferschein im PDF Format{{ $deliveryNote ? ' (Ohne Auswahl wird der aktuelle Lieferschein beibehalten)' : '' }}</label>
            <div class="custom-file">
                <input type="file" accept="application/pdf" class="custom-file-input" id="document"
                       name="document">
                <label class="custom-file-label" for="document">Lieferschein auswählen</label>
            </div>
            <div class="invalid-feedback @error('document') d-block @enderror">
                @error('document')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>

</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
            </svg>
            Anfrage zur Unterschrift senden
        </p>
        <p class="text-muted">
            Bei Aktivierung der Schaltfläche kann nach dem Speichern direkt eine Anfrage zur Unterschrift per Email versendet werden.
        </p>
    </div>

    <div class="col-md-8">
        <div class="alert alert-info" role="alert">
            <div class="d-inline-flex align-items-center">
                <svg class="icon icon-24 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                </svg>
                Die Email Adresse kann im nächsten Schritt angegeben werden.
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('send_signature_request') is-invalid @enderror" name="send_signature_request" id="send_signature_request" value="true">
                <label class="custom-control-label" for="send_signature_request">Anfrage zur Unterschrift nach dem Speichern senden.</label>
            </div>
            <div class="invalid-feedback @error('send_signature_request') d-block @enderror">
                @error('send_signature_request')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>
