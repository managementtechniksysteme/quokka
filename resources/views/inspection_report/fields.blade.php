@php
    use \App\Models\Project;
@endphp

@if (old('project_id'))
    @php $currentProject = Project::find(old('project_id')); @endphp
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
        <div class="q-form-section__desc">Die Stammdaten des Prüfberichtes. Bei der Bearbeitung eines bereits unterschriebenen Prüfberichtes wird die vorhandene Unterschrift entfernt.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="employee">Techniker</label>
            <input type="text" class="form-control" name="employee" id="employee" placeholder="{{ optional($inspectionReport)->employee->person->name ?? Auth::user()->person->name }}" disabled />
        </div>

        <div>
            <label>Status</label>
            @if(optional($inspectionReport)->status === 'signed')
                <div class="q-banner">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
                    <span>Der Prüfbericht wurde bereits unterschrieben. Beim Speichern wird die aktuelle Unterschrift entfernt! Eine erneute Anfrage zum Unterschreiben kann gesendet werden.</span>
                </div>
            @endif
            <div class="btn-group">
                <input type="radio" class="btn-check" name="status" id="status-new" value="new" autocomplete="off" @if(optional($inspectionReport)->status == 'new' || !$inspectionReport) checked @endif disabled>
                <label class="btn btn-outline-secondary q-seg--sky" for="status-new">neu</label>
                <input type="radio" class="btn-check" name="status" id="status-signed" value="signed" autocomplete="off" @if(optional($inspectionReport)->status == 'signed') checked @endif disabled>
                <label class="btn btn-outline-secondary q-seg--amber" for="status-signed">unterschrieben</label>
                <input type="radio" class="btn-check" name="status" id="status-finished" value="finished" autocomplete="off" @if(optional($inspectionReport)->status == 'finished') checked @endif disabled>
                <label class="btn btn-outline-secondary q-seg--green" for="status-finished">erledigt</label>
            </div>
        </div>

        <div>
            <label for="inspected_on">Datum</label>
            <input type="date" class="form-control @error('inspected_on') is-invalid @enderror" id="inspected_on" name="inspected_on" value="{{ old('inspected_on', optional(optional($inspectionReport)->inspected_on)->format('Y-m-d')) }}" required />
            <div class="invalid-feedback">
                @error('inspected_on')
                    {{ $message }}
                @else
                    Gib bitte das Datum der Überprüfung ein.
                @enderror
            </div>
        </div>

        <div class="q-form__row q-form__row--2">
            <div>
                <label for="equipment_type">Anlagentyp</label>
                <input type="text" class="form-control @error('equipment_type') is-invalid @enderror" id="equipment_type" name="equipment_type" placeholder="2AF300T" value="{{ old('equipment_type', optional($inspectionReport)->equipment_type) }}" required />
                <div class="invalid-feedback">
                    @error('equipment_type')
                        {{ $message }}
                    @else
                        Gib bitte den Anlagentyp ein.
                    @enderror
                </div>
            </div>
            <div>
                <label for="equipment_identifier">Anlagen-/Gerätenummer</label>
                <input type="text" class="form-control @error('equipment_identifier') is-invalid @enderror" id="equipment_identifier" name="equipment_identifier" placeholder="A012345.1234" value="{{ old('equipment_identifier', optional($inspectionReport)->equipment_identifier) }}" required />
                <div class="invalid-feedback">
                    @error('equipment_identifier')
                        {{ $message }}
                    @else
                        Gib bitte die Anlagen-/Gerätenummer ein.
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label>Wetter</label>
            <div class="btn-group @error('weather') is-invalid @enderror">
                <input type="radio" class="btn-check" name="weather" id="weather-sunny" value="sunny" autocomplete="off" @if(old('weather', optional($inspectionReport)->weather) == 'sunny') checked @endif>
                <label class="btn btn-outline-secondary q-seg--amber" for="weather-sunny">sonnig</label>
                <input type="radio" class="btn-check" name="weather" id="weather-cloudy" value="cloudy" autocomplete="off" @if(old('weather', optional($inspectionReport)->weather) == 'cloudy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-cloudy">bewölkt</label>
                <input type="radio" class="btn-check" name="weather" id="weather-rainy" value="rainy" autocomplete="off" @if(old('weather', optional($inspectionReport)->weather) == 'rainy') checked @endif>
                <label class="btn btn-outline-secondary q-seg--sky" for="weather-rainy">regnerisch</label>
                <input type="radio" class="btn-check" name="weather" id="weather-snowy" value="snowy" autocomplete="off" @if(old('weather', optional($inspectionReport)->weather) == 'snowy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-snowy">Schnee</label>
            </div>
            <div class="invalid-feedback @error('weather') d-block @enderror">
                @error('weather')
                    {{ $message }}
                @enderror
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

<div class="q-form-section">
    <div class="q-form-section__head">
        UVC Strahler
        <div class="q-form-section__desc">Angaben zum UVC Strahler.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div class="q-form__row q-form__row--2">
            <div>
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
            <div>
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
        </div>

        <div class="q-form__row q-form__row--2">
            <div>
                <label for="uvc_lamp_operating_hours">Betriebsstunden</label>
                <div class="input-group has-validation">
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
            <div>
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
        </div>

        <div class="q-form__row q-form__row--2">
            <div>
                <label for="uvc_lamp_uv_intensity_arrival">UV Intensität bei Ankunft</label>
                <div class="input-group has-validation">
                    <input type="number" min="0" step="any" class="form-control @error('uvc_lamp_uv_intensity_arrival') is-invalid @enderror" id="uvc_lamp_uv_intensity_arrival" name="uvc_lamp_uv_intensity_arrival" placeholder="73" value="{{ old('uvc_lamp_uv_intensity_arrival', optional($inspectionReport)->uvc_lamp_uv_intensity_arrival) }}" required />
                    <span class="input-group-text">% oder W/m²</span>
                    <div class="invalid-feedback">
                        @error('uvc_lamp_uv_intensity_arrival')
                            {{ $message }}
                        @else
                            Gib bitte die UV Intensität bei Ankunft ein.
                        @enderror
                    </div>
                </div>
            </div>
            <div>
                <label for="uvc_lamp_uv_intensity_departure">UV Intensität bei Abfahrt</label>
                <div class="input-group has-validation">
                    <input type="number" min="0" step="any" class="form-control @error('uvc_lamp_uv_intensity_departure') is-invalid @enderror" id="uvc_lamp_uv_intensity_departure" name="uvc_lamp_uv_intensity_departure" placeholder="103" value="{{ old('uvc_lamp_uv_intensity_departure', optional($inspectionReport)->uvc_lamp_uv_intensity_departure) }}" required />
                    <span class="input-group-text">% oder W/m²</span>
                    <div class="invalid-feedback">
                        @error('uvc_lamp_uv_intensity_departure')
                            {{ $message }}
                        @else
                            Gib bitte die UV Intensität bei Abfahrt ein.
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label>Angaben der UV Intensitäten in</label>
            <div class="btn-group @error('uvc_lamp_values_unit') is-invalid @enderror">
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

        <div>
            <label>Ersatzstrahler vorhanden</label>
            <div class="btn-group @error('uvc_lamp_replacement_available') is-invalid @enderror">
                <input type="radio" class="btn-check" name="uvc_lamp_replacement_available" id="uvc_lamp_replacement_available-1" value="1" autocomplete="off" @if(old('uvc_lamp_replacement_available') === '1' || ($inspectionReport && $inspectionReport->uvc_lamp_replacement_available === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="uvc_lamp_replacement_available-1">ja</label>
                <input type="radio" class="btn-check" name="uvc_lamp_replacement_available" id="uvc_lamp_replacement_available-0" value="0" autocomplete="off" @if(old('uvc_lamp_replacement_available') === '0' || ($inspectionReport && $inspectionReport->uvc_lamp_replacement_available === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="uvc_lamp_replacement_available-0">nein</label>
                <input type="radio" class="btn-check" name="uvc_lamp_replacement_available" id="uvc_lamp_replacement_available-null" value="" autocomplete="off" @if((old('_token') && old('uvc_lamp_replacement_available') === null) || ($inspectionReport && $inspectionReport->uvc_lamp_replacement_available === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="uvc_lamp_replacement_available-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('uvc_lamp_replacement_available') d-block @enderror">
                @error('uvc_lamp_replacement_available')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        UVC Sensor
        <div class="q-form-section__desc">Angaben zum UVC Sensor.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div class="q-form__row q-form__row--2">
            <div>
                <label for="uvc_sensor_type">Typ</label>
                <input type="text" class="form-control @error('uvc_sensor_type') is-invalid @enderror" id="uvc_sensor_type" name="uvc_sensor_type" placeholder="Diginorm" value="{{ old('uvc_sensor_type', optional($inspectionReport)->uvc_sensor_type) }}" required />
                <div class="invalid-feedback">
                    @error('uvc_sensor_type')
                        {{ $message }}
                    @else
                        Gib bitte den Typ des UVC Sensors ein.
                    @enderror
                </div>
            </div>
            <div>
                <label for="uvc_sensor_identifier">Seriennummer</label>
                <input type="text" class="form-control @error('uvc_sensor_identifier') is-invalid @enderror" id="uvc_sensor_identifier" name="uvc_sensor_identifier" placeholder="1234abc89def" value="{{ old('uvc_sensor_identifier', optional($inspectionReport)->uvc_sensor_identifier) }}" required />
                <div class="invalid-feedback">
                    @error('uvc_sensor_identifier')
                        {{ $message }}
                    @else
                        Gib bitte die Seriennummer des UVC Sensors ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="q-form__row q-form__row--2">
            <div>
                <label for="uvc_sensor_pre_alarm">Voralarm</label>
                <div class="input-group has-validation">
                    <input type="number" min="0" step="any" class="form-control @error('uvc_sensor_pre_alarm') is-invalid @enderror" id="uvc_sensor_pre_alarm" name="uvc_sensor_pre_alarm" placeholder="68" value="{{ old('uvc_sensor_pre_alarm', optional($inspectionReport)->uvc_sensor_pre_alarm) }}" required />
                    <span class="input-group-text">% oder W/m²</span>
                    <div class="invalid-feedback">
                        @error('uvc_sensor_pre_alarm')
                            {{ $message }}
                        @else
                            Gib bitte den Voralarm des UVC Sensors ein.
                        @enderror
                    </div>
                </div>
            </div>
            <div>
                <label for="uvc_sensor_cut_off_point">Abschaltpunkt</label>
                <div class="input-group has-validation">
                    <input type="number" min="0" step="any" class="form-control @error('uvc_sensor_cut_off_point') is-invalid @enderror" id="uvc_sensor_cut_off_point" name="uvc_sensor_cut_off_point" placeholder="62.9" value="{{ old('uvc_sensor_cut_off_point', optional($inspectionReport)->uvc_sensor_cut_off_point) }}" required />
                    <span class="input-group-text">% oder W/m²</span>
                    <div class="invalid-feedback">
                        @error('uvc_sensor_cut_off_point')
                            {{ $message }}
                        @else
                            Gib bitte den Abschaltpunkt des UVC Sensors ein.
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label>Angaben der Werte in</label>
            <div class="btn-group @error('uvc_sensor_values_unit') is-invalid @enderror">
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

<div class="q-form-section">
    <div class="q-form-section__head">
        Überprüfung der Quartzschutzrohre
        <div class="q-form-section__desc">Angaben zum Zustand der Quartzschutzrohre.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label>Verschmutzung</label>
            <div class="btn-group @error('quartz_tube_contaminated') is-invalid @enderror">
                <input type="radio" class="btn-check" name="quartz_tube_contaminated" id="quartz_tube_contaminated-1" value="1" autocomplete="off" @if(old('quartz_tube_contaminated') === '1' || ($inspectionReport && $inspectionReport->quartz_tube_contaminated === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="quartz_tube_contaminated-1">ja</label>
                <input type="radio" class="btn-check" name="quartz_tube_contaminated" id="quartz_tube_contaminated-0" value="0" autocomplete="off" @if(old('quartz_tube_contaminated') === '0' || ($inspectionReport && $inspectionReport->quartz_tube_contaminated === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="quartz_tube_contaminated-0">nein</label>
                <input type="radio" class="btn-check" name="quartz_tube_contaminated" id="quartz_tube_contaminated-null" value="" autocomplete="off" @if((old('_token') && old('quartz_tube_contaminated') === null) || ($inspectionReport && $inspectionReport->quartz_tube_contaminated === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="quartz_tube_contaminated-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('quartz_tube_contaminated') d-block @enderror">
                @error('quartz_tube_contaminated')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Undicht</label>
            <div class="btn-group @error('quartz_tube_leaking') is-invalid @enderror">
                <input type="radio" class="btn-check" name="quartz_tube_leaking" id="quartz_tube_leaking-1" value="1" autocomplete="off" @if(old('quartz_tube_leaking') === '1' || ($inspectionReport && $inspectionReport->quartz_tube_leaking === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="quartz_tube_leaking-1">ja</label>
                <input type="radio" class="btn-check" name="quartz_tube_leaking" id="quartz_tube_leaking-0" value="0" autocomplete="off" @if(old('quartz_tube_leaking') === '0' || ($inspectionReport && $inspectionReport->quartz_tube_leaking === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="quartz_tube_leaking-0">nein</label>
                <input type="radio" class="btn-check" name="quartz_tube_leaking" id="quartz_tube_leaking-null" value="" autocomplete="off" @if((old('_token') && old('quartz_tube_leaking') === null) || ($inspectionReport && $inspectionReport->quartz_tube_leaking === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="quartz_tube_leaking-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('quartz_tube_leaking') d-block @enderror">
                @error('quartz_tube_leaking')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Wasser
        <div class="q-form-section__desc">Angaben zum Zustand des Wassers.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="water_flow_rate">Durchfluss</label>
            <div class="input-group has-validation">
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

        <div class="q-form__row q-form__row--2">
            <div>
                <label for="water_minimum_uv_transmission">Minimale Transmission[100mm]</label>
                <div class="input-group has-validation">
                    <input type="number" min="0" max="100" step="any" class="form-control @error('water_minimum_uv_transmission') is-invalid @enderror" id="water_minimum_uv_transmission" name="water_minimum_uv_transmission" placeholder="15" value="{{ old('water_minimum_uv_transmission', optional($inspectionReport)->water_minimum_uv_transmission) }}" required />
                    <span class="input-group-text">%</span>
                    <div class="invalid-feedback">
                        @error('water_minimum_uv_transmission')
                            {{ $message }}
                        @else
                            Gib bitte die minimale Transmission[100mm] ein.
                        @enderror
                    </div>
                </div>
            </div>
            <div>
                <label for="water_measured_uv_transmission">Gemessene Transmission[100mm]</label>
                <div class="input-group has-validation">
                    <input type="number" min="0" max="100" step="any" class="form-control @error('water_measured_uv_transmission') is-invalid @enderror" id="water_measured_uv_transmission" name="water_measured_uv_transmission" placeholder="26" value="{{ old('water_measured_uv_transmission', optional($inspectionReport)->water_measured_uv_transmission) }}" required />
                    <span class="input-group-text">%</span>
                    <div class="invalid-feedback">
                        @error('water_measured_uv_transmission')
                            {{ $message }}
                        @else
                            Gib bitte die gemessene Transmission[100mm] ein.
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label>Schwebestoffe sichtbar</label>
            <div class="btn-group @error('water_suspended_load_visible') is-invalid @enderror">
                <input type="radio" class="btn-check" name="water_suspended_load_visible" id="water_suspended_load_visible-1" value="1" autocomplete="off" @if(old('water_suspended_load_visible') === '1' || ($inspectionReport && $inspectionReport->water_suspended_load_visible === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="water_suspended_load_visible-1">ja</label>
                <input type="radio" class="btn-check" name="water_suspended_load_visible" id="water_suspended_load_visible-0" value="0" autocomplete="off" @if(old('water_suspended_load_visible') === '0' || ($inspectionReport && $inspectionReport->water_suspended_load_visible === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="water_suspended_load_visible-0">nein</label>
                <input type="radio" class="btn-check" name="water_suspended_load_visible" id="water_suspended_load_visible-null" value="" autocomplete="off" @if((old('_token') && old('water_suspended_load_visible') === null) || ($inspectionReport && $inspectionReport->water_suspended_load_visible === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="water_suspended_load_visible-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('water_suspended_load_visible') d-block @enderror">
                @error('water_suspended_load_visible')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Luftblasenfrei</label>
            <div class="btn-group @error('water_air_bubble_free') is-invalid @enderror">
                <input type="radio" class="btn-check" name="water_air_bubble_free" id="water_air_bubble_free-1" value="1" autocomplete="off" @if(old('water_air_bubble_free') === '1' || ($inspectionReport && $inspectionReport->water_air_bubble_free === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="water_air_bubble_free-1">ja</label>
                <input type="radio" class="btn-check" name="water_air_bubble_free" id="water_air_bubble_free-0" value="0" autocomplete="off" @if(old('water_air_bubble_free') === '0' || ($inspectionReport && $inspectionReport->water_air_bubble_free === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="water_air_bubble_free-0">nein</label>
                <input type="radio" class="btn-check" name="water_air_bubble_free" id="water_air_bubble_free-null" value="" autocomplete="off" @if((old('_token') && old('water_air_bubble_free') === null) || ($inspectionReport && $inspectionReport->water_air_bubble_free === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="water_air_bubble_free-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('water_air_bubble_free') d-block @enderror">
                @error('water_air_bubble_free')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Durchgeführte Arbeiten und Bemerkungen
        <div class="q-form-section__desc">Durchgeführte Arbeiten während der Überprüfung sowie sonstige Bemerkungen.</div>
    </div>
    <div class="q-form-section__body">
        <markdown-editor name="comment" placeholder="Durchgeführte Arbeiten und Bemerkungen" value="{{ old('comment', optional($inspectionReport)->comment) }}" v-cloak></markdown-editor>
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
        <div class="q-form-section__desc">Dem Prüfbericht zugeordnete Anhänge. Erlaubt sind Dateien im Bildformat oder PDF Dokumente.</div>
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
