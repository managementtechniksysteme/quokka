@php
    use \App\Models\Project;
@endphp

@if (old('project_id'))
    @php $currentProject = Project::find(old('project_id')); @endphp
@endif

@csrf

@unless(Auth::user()->signature())
    <div class="alert alert-warning mt-1" role="alert">
        <div class="d-inline-flex align-items-center">
            <svg class="icon icon-24 me-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
            </svg>
            <p class="m-0">
                Du hast noch keine Unterschrift im System hinterlegt. Es kann nicht automatisch
                eine Unterschrift in PDF Ausdrucke von Berichten eingefügt werden. Füge bitte eine Unterschrift in den
                <a href="{{ route('user-settings.edit', ['tab' => 'general']) }}">allgemeinen Einstellungen</a>
                hinzu.
            </p>
        </div>
    </div>
@endunless

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 me-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
            </svg>
            Stammdaten
        </p>
        <p class="text-muted">
            Die Stammdaten des Prüfberichtes.
        </p>
        <p class="text-muted">
            Bei der Bearbeitung eines bereits unterschriebenen Prüfberichtes wird die vorhandene Unterschrift entferent.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <label for="employee">Techniker</label>
            <input type="text" class="form-control" name="employee" id="employee" placeholder="{{ optional($inspectionReport)->employee->person->name ?? Auth::user()->person->name }}" disabled />
        </div>

        <div class="mb-3">
            <div>
                <label for="status">Status</label>
            </div>
            @if(optional($inspectionReport)->status === 'signed')
                <div class="alert alert-warning mt-1" role="alert">
                    <div class="d-inline-flex align-items-center">
                        <svg class="icon icon-24 me-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                        </svg>
                        Der Prüfbericht wurde bereits unterschrieben. Beim Speichern wird die aktuelle Unterschrift entfernt! Eine erneute Anfrage zum Unterschreiben kann gesendet werden.
                    </div>
                </div>
            @endif
            <div class="btn-group">
                <input type="radio" class="btn-check" name="weather" id="weather-sunny" value="sunny" autocomplete="off" @if(old('weather', optional($inspectionReport)->weather) == 'sunny') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-sunny">sonnig</label>
                <input type="radio" class="btn-check" name="weather" id="weather-cloudy" value="cloudy" autocomplete="off" @if(old('weather', optional($inspectionReport)->weather) == 'cloudy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-cloudy">bewölkt</label>
                <input type="radio" class="btn-check" name="weather" id="weather-rainy" value="rainy" autocomplete="off" @if(old('weather', optional($inspectionReport)->weather) == 'rainy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-rainy">regnerisch</label>
                <input type="radio" class="btn-check" name="weather" id="weather-snowy" value="snowy" autocomplete="off" @if(old('weather', optional($inspectionReport)->weather) == 'snowy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-snowy">Schnee</label>
            </div>
            <div class="invalid-feedback @error('weather') d-block @enderror">
                @error('weather')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="mb-3">
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
            <svg class="icon icon-16 me-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>
            </svg>
            UVC Strahler
        </p>
        <p class="text-muted">
            Angaben zum UVC Strahler.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <label for="uvc_lamp_quantity">Anzahl</label>
            <input type="number" min="1" step="1" class="form-control @error('uvc_lamp_quantity') is-invalid @enderror" id="uvc_lamp_quantity" name="uvc_lamp_quantity" placeholder="2" value="{{ old('uvc_lamp_quantity', optional($inspectionReport)->uvc_lamp_quantity) }}" required />
            <div class="invalid-feedback">
                @error('uvc_lamp_quantity')
                    {{ $message }}
                @else
                    Gib bitte die Anzahl der UVC Strahler ein.
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="uvc_lamp_type">Typ</label>
            <input type="text" class="form-control @error('uvc_lamp_type') is-invalid @enderror" id="uvc_lamp_type" name="uvc_lamp_type" placeholder="AF300T" value="{{ old('uvc_lamp_type', optional($inspectionReport)->uvc_lamp_type) }}" required />
            <div class="invalid-feedback">
                @error('uvc_lamp_type')
                    {{ $message }}
                @else
                    Gib bitte den Typ der UVC Strahler ein.
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="uvc_lamp_operating_hours">Betriebsstunden</label>
            <div class="input-group">
                <input type="number" min="0" step="1" class="form-control @error('uvc_lamp_operating_hours') is-invalid @enderror" id="uvc_lamp_operating_hours" name="uvc_lamp_operating_hours" placeholder="6800" value="{{ old('uvc_lamp_operating_hours', optional($inspectionReport)->uvc_lamp_operating_hours) }}" required />
                    <span class="input-group-text">h</span>
                <div class="invalid-feedback">
                    @error('uvc_lamp_operating_hours')
                        {{ $message }}
                    @else
                        Gib bitte die Betriebsstunden der UVC Strahler ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="uvc_lamp_impulses">Impulse</label>
            <input type="number" min="0" step="1" class="form-control @error('uvc_lamp_impulses') is-invalid @enderror" id="uvc_lamp_impulses" name="uvc_lamp_impulses" placeholder="80" value="{{ old('uvc_lamp_impulses', optional($inspectionReport)->uvc_lamp_impulses) }}" required />
            <div class="invalid-feedback">
                @error('uvc_lamp_impulses')
                    {{ $message }}
                @else
                    Gib bitte die Impulse der UVC Strahler ein.
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="uvc_lamp_uv_intensity_arrival">UV Intensität bei Ankunft</label>
            <div class="input-group">
                <input type="number" min="0" step="any" class="form-control @error('uvc_lamp_uv_intensity_arrival') is-invalid @enderror" id="uvc_lamp_uv_intensity_arrival" name="uvc_lamp_uv_intensity_arrival" placeholder="73" value="{{ old('uvc_lamp_uv_intensity_arrival', optional($inspectionReport)->uvc_lamp_uv_intensity_arrival) }}" required />
                    <span class="input-group-text">% oder W/m²</span>
                <div class="invalid-feedback">
                    @error('uvc_lamp_uv_intensity_arrival')
                        {{ $message }}
                    @else
                        Gib bitte die UV Intensität der UVC Strahler bei Ankunft ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="uvc_lamp_uv_intensity_departure">UV Intensität bei Abfahrt</label>
            <div class="input-group">
                <input type="number" min="0" step="any" class="form-control @error('uvc_lamp_uv_intensity_departure') is-invalid @enderror" id="uvc_lamp_uv_intensity_departure" name="uvc_lamp_uv_intensity_departure" placeholder="103" value="{{ old('uvc_lamp_uv_intensity_departure', optional($inspectionReport)->uvc_lamp_uv_intensity_departure) }}" required />
                    <span class="input-group-text">% oder W/m²</span>
                <div class="invalid-feedback">
                    @error('uvc_lamp_uv_intensity_departure')
                        {{ $message }}
                    @else
                        Gib bitte die UV Intensität der UVC Strahler bei Abfahrt ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <div>
                <label for="uvc_lamp_values_unit">Angaben der UV Intensitäten in</label>
            </div>
            <div class="btn-group @error('uvc_lamp_values_unit') is-invalid @enderror" >
                <input type="radio" class="btn-check" name="uvc_lamp_values_unit" id="uvc_lamp_values_unit-percent" value="percent" autocomplete="off" @if(old('uvc_lamp_values_unit', optional($inspectionReport)->uvc_lamp_values_unit) == 'percent') checked @endif>
                <label class="btn btn-outline-secondary" for="uvc_lamp_values_unit-percent">%</label>
                <input type="radio" class="btn-check" name="uvc_lamp_values_unit" id="uvc_lamp_values_unit-W_m2" value="W_m2" autocomplete="off" @if(old('uvc_lamp_values_unit', optional($inspectionReport)->uvc_lamp_values_unit) == 'W_m2') checked @endif>
                <label class="btn btn-outline-secondary" for="uvc_lamp_values_unit-W_m2">W/m²</label>
            </div>
            <div class="invalid-feedback @error('uvc_lamp_values_unit') d-block @enderror">
                @error('uvc_lamp_values_unit')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <div>
                <label for="uvc_lamp_replacement_available">Ersatzstrahler vorhanden</label>
            </div>
            <div class="btn-group @error('uvc_lamp_replacement_available') is-invalid @enderror" >
                <input type="radio" class="btn-check" name="uvc_sensor_values_unit" id="uvc_sensor_values_unit-percent" value="percent" autocomplete="off" @if(old('uvc_sensor_values_unit', optional($inspectionReport)->uvc_sensor_values_unit) == 'percent') checked @endif>
                <label class="btn btn-outline-secondary" for="uvc_sensor_values_unit-percent">%</label>
                <input type="radio" class="btn-check" name="uvc_sensor_values_unit" id="uvc_sensor_values_unit-W_m2" value="W_m2" autocomplete="off" @if(old('uvc_sensor_values_unit', optional($inspectionReport)->uvc_sensor_values_unit) == 'W_m2') checked @endif>
                <label class="btn btn-outline-secondary" for="uvc_sensor_values_unit-W_m2">W/m²</label>
            </div>
            <div class="invalid-feedback @error('uvc_sensor_values_unit') d-block @enderror">
                @error('uvc_sensor_values_unit')
                    {{ $message }}
                @enderror
            </div>
        </div>

    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 me-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#circle"></use>
            </svg>
            Überprüfung der Quartzschutzrohre
        </p>
        <p class="text-muted">
            Angaben zum Zustand der Quartzschutzrohre.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <div>
                <label for="quartz_tube_contaminated">Verschmutzung</label>
            </div>
            <div class="btn-group @error('quartz_tube_contaminated') is-invalid @enderror" >
                <input type="radio" class="btn-check" name="quartz_tube_contaminated" id="quartz_tube_contaminated-1" value="1" autocomplete="off" @if(old('quartz_tube_contaminated') === '1' || ($inspectionReport && $inspectionReport->quartz_tube_contaminated === true)) checked @endif>
                <label class="btn btn-outline-secondary" for="quartz_tube_contaminated-1">ja</label>
                <input type="radio" class="btn-check" name="quartz_tube_contaminated" id="quartz_tube_contaminated-0" value="0" autocomplete="off" @if(old('quartz_tube_contaminated') === '0' || ($inspectionReport && $inspectionReport->quartz_tube_contaminated === false)) checked @endif>
                <label class="btn btn-outline-secondary" for="quartz_tube_contaminated-0">nein</label>
                <input type="radio" class="btn-check" name="quartz_tube_contaminated" id="quartz_tube_contaminated-none" value="" autocomplete="off" @if((old('_token') && old('quartz_tube_contaminated') === null) || ($inspectionReport && $inspectionReport->quartz_tube_contaminated === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="quartz_tube_contaminated-none">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('quartz_tube_contaminated') d-block @enderror">
                @error('quartz_tube_contaminated')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <div>
                <label for="quartz_tube_leaking">Undicht</label>
            </div>
            <div class="btn-group @error('quartz_tube_leaking') is-invalid @enderror" >
                <input type="radio" class="btn-check" name="quartz_tube_leaking" id="quartz_tube_leaking-1" value="1" autocomplete="off" @if(old('quartz_tube_leaking') === '1' || ($inspectionReport && $inspectionReport->quartz_tube_leaking === true)) checked @endif>
                <label class="btn btn-outline-secondary" for="quartz_tube_leaking-1">ja</label>
                <input type="radio" class="btn-check" name="quartz_tube_leaking" id="quartz_tube_leaking-0" value="0" autocomplete="off" @if(old('quartz_tube_leaking') === '0' || ($inspectionReport && $inspectionReport->quartz_tube_leaking === false)) checked @endif>
                <label class="btn btn-outline-secondary" for="quartz_tube_leaking-0">nein</label>
                <input type="radio" class="btn-check" name="quartz_tube_leaking" id="quartz_tube_leaking-none" value="" autocomplete="off" @if((old('_token') && old('quartz_tube_leaking') === null) || ($inspectionReport && $inspectionReport->quartz_tube_leaking === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="quartz_tube_leaking-none">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('quartz_tube_leaking') d-block @enderror">
                @error('quartz_tube_leaking')
                    {{ $message }}
                @enderror
            </div>
        </div>

    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 me-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#droplet"></use>
            </svg>
            Wasser
        </p>
        <p class="text-muted">
            Angaben zum Zustand des Wassers.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <label for="water_flow_rate">Durchfluss</label>
            <div class="input-group">
                <input type="number" min="0" step="any" class="form-control @error('water_flow_rate') is-invalid @enderror" id="water_flow_rate" name="water_flow_rate" placeholder="10" value="{{ old('water_flow_rate', optional($inspectionReport)->water_flow_rate) }}" required />
                    <span class="input-group-text">m³/h</span>
                <div class="invalid-feedback">
                    @error('water_flow_rate')
                        {{ $message }}
                    @else
                        Gib bitte den Durchfluss des Wassers ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="water_minimum_uv_transmission">minimale Transmission[100mm]</label>
            <div class="input-group">
                <input type="number" min="0" max="100" step="any" class="form-control @error('water_minimum_uv_transmission') is-invalid @enderror" id="water_minimum_uv_transmission" name="water_minimum_uv_transmission" placeholder="15" value="{{ old('water_minimum_uv_transmission', optional($inspectionReport)->water_minimum_uv_transmission) }}" required />
                    <span class="input-group-text">%</span>
                <div class="invalid-feedback">
                    @error('water_minimum_uv_transmission')
                    {{ $message }}
                    @else
                        Gib bitte die minimale Transmission[100mm] des Wassers ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="water_measured_uv_transmission">gemessene Transmission[100mm]</label>
            <div class="input-group">
                <input type="number" min="0" max="100" step="any" class="form-control @error('water_measured_uv_transmission') is-invalid @enderror" id="water_measured_uv_transmission" name="water_measured_uv_transmission" placeholder="26" value="{{ old('water_measured_uv_transmission', optional($inspectionReport)->water_measured_uv_transmission) }}" required />
                    <span class="input-group-text">%</span>
                <div class="invalid-feedback">
                    @error('water_measured_uv_transmission')
                    {{ $message }}
                    @else
                        Gib bitte die gemessene Transmission[100mm] des Wassers ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <div>
                <label for="water_suspended_load_visible">Schwebestoffe sichtbar</label>
            </div>
            <div class="btn-group @error('water_suspended_load_visible') is-invalid @enderror" >
                <input type="radio" class="btn-check" name="water_suspended_load_visible" id="water_suspended_load_visible-1" value="1" autocomplete="off" @if(old('water_suspended_load_visible') === '1' || ($inspectionReport && $inspectionReport->water_suspended_load_visible === true)) checked @endif>
                <label class="btn btn-outline-secondary" for="water_suspended_load_visible-1">ja</label>
                <input type="radio" class="btn-check" name="water_suspended_load_visible" id="water_suspended_load_visible-0" value="0" autocomplete="off" @if(old('water_suspended_load_visible') === '0' || ($inspectionReport && $inspectionReport->water_suspended_load_visible === false)) checked @endif>
                <label class="btn btn-outline-secondary" for="water_suspended_load_visible-0">nein</label>
                <input type="radio" class="btn-check" name="water_suspended_load_visible" id="water_suspended_load_visible-none" value="" autocomplete="off" @if((old('_token') && old('water_suspended_load_visible') === null) || ($inspectionReport && $inspectionReport->water_suspended_load_visible === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="water_suspended_load_visible-none">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('water_suspended_load_visible') d-block @enderror">
                @error('water_suspended_load_visible')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <div>
                <label for="water_air_bubble_free">Luftblasenfrei</label>
            </div>
            <div class="btn-group @error('water_air_bubble_free') is-invalid @enderror" >
                <input type="radio" class="btn-check" name="water_air_bubble_free" id="water_air_bubble_free-1" value="1" autocomplete="off" @if(old('water_air_bubble_free') === '1' || ($inspectionReport && $inspectionReport->water_air_bubble_free === true)) checked @endif>
                <label class="btn btn-outline-secondary" for="water_air_bubble_free-1">ja</label>
                <input type="radio" class="btn-check" name="water_air_bubble_free" id="water_air_bubble_free-0" value="0" autocomplete="off" @if(old('water_air_bubble_free') === '0' || ($inspectionReport && $inspectionReport->water_air_bubble_free === false)) checked @endif>
                <label class="btn btn-outline-secondary" for="water_air_bubble_free-0">nein</label>
                <input type="radio" class="btn-check" name="water_air_bubble_free" id="water_air_bubble_free-none" value="" autocomplete="off" @if((old('_token') && old('water_air_bubble_free') === null) || ($inspectionReport && $inspectionReport->water_air_bubble_free === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="water_air_bubble_free-none">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('water_air_bubble_free') d-block @enderror">
                @error('water_air_bubble_free')
                    {{ $message }}
                @enderror
            </div>
        </div>

    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 me-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Durchgeführte Arbeiten und Bemerkungen
        </p>
        <p class="text-muted">
            Durchgeführte Arbeiten während der Überprüfung sowie sonstige Bemerkungen.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <label for="comment">
                Durchgeführte Arbeiten und Bemerkungen
            </label>
            <markdown-editor name="comment" placeholder="Leistungsfortschritt"  value="{{ old('comment', optional($inspectionReport)->comment) }}" v-cloak></markdown-editor>
            <a class="text-muted d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
                <svg class="icon icon-16 me-1">
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
            <svg class="icon icon-16 me-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
            </svg>
            Anhänge
        </p>
        <p class="text-muted">
            Dem Prüfbericht zugeordnete Anhänge. Erlaubt sind Dateien im Bildformat oder PDF Dokumente.
        </p>
        <p class="text-muted">
            Der Dateiname von neu hinzugefügten Anhängen kann geändert werden, indem der Text markiert und ein neuer Name eingegeben wird.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <label>
                Anhänge
            </label>
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
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 me-2">
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
                <svg class="icon icon-24 me-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                </svg>
                Die Email Adresse kann im nächsten Schritt angegeben werden.
            </div>
        </div>
        <div class="mb-3">
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
