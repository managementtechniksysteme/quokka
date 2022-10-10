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
            <svg class="icon icon-24 mr-2">
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
            <svg class="icon-bs icon-16 mr-2">
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
        <div class="form-group">
            <label for="employee">Techniker</label>
            <input type="text" class="form-control" name="employee" id="employee" placeholder="{{ optional($inspectionReport)->employee->person->name ?? Auth::user()->person->name }}" disabled />
        </div>

        <div class="form-group">
            <div>
                <label for="status">Status</label>
            </div>
            @if(optional($inspectionReport)->status === 'signed')
                <div class="alert alert-warning mt-1" role="alert">
                    <div class="d-inline-flex align-items-center">
                        <svg class="icon icon-24 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                        </svg>
                        Der Prüfbericht wurde bereits unterschrieben. Beim Speichern wird die aktuelle Unterschrift entfernt! Eine erneute Anfrage zum Unterschreiben kann gesendet werden.
                    </div>
                </div>
            @endif
            <div class="btn-group btn-group-toggle">
                <label class="btn btn-outline-secondary @if(optional($inspectionReport)->status == 'new' || !$inspectionReport) active @else disabled @endif">
                    <input type="radio" name="status" id="new" @if(optional($inspectionReport)->status == 'new' || !$inspectionReport) checked @endif disabled> neu
                </label>
                <label class="btn btn-outline-secondary @if(optional($inspectionReport)->status == 'signed') active @else disabled  @endif">
                    <input type="radio" name="status" id="signed" @if(optional($inspectionReport)->status == 'signed') checked @endif disabled> unterschrieben
                </label>
                <label class="btn btn-outline-secondary @if(optional($inspectionReport)->status == 'finished') active @else disabled  @endif">
                    <input type="radio" name="status" id="finished" @if(optional($inspectionReport)->status == 'finished') checked @endif disabled> erledigt
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="inspected_on">Datum</label>
            <input type="date" class="form-control @error('inspected_on') is-invalid @enderror" id="inspected_on" name="inspected_on" placeholder="" value="{{ old('inspected_on', optional(optional($inspectionReport)->inspected_on)->format('Y-m-d')) }}" required />
            <div class="invalid-feedback">
                @error('inspected_on')
                {{ $message }}
                @else
                    Gib bitte das Datum der Überprüfung ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
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

        <div class="form-group">
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

        <div class="form-group">
            <div>
                <label for="priority">Wetter</label>
            </div>
            <div class="btn-group btn-group-toggle @error('weather') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('weather', optional($inspectionReport)->weather) == 'sunny') active @endif">
                    <input type="radio" name="weather" id="sunny" value="sunny" autocomplete="off" @if(old('weather', optional($inspectionReport)->weather) == 'sunny') checked @endif> sonnig
                </label>
                <label class="btn btn-outline-secondary @if(old('weather', optional($inspectionReport)->weather) == 'cloudy') active @endif">
                    <input type="radio" name="weather" id="cloudy" value="cloudy" autocomplete="off" @if(old('weather', optional($inspectionReport)->weather) == 'cloudy') checked @endif> bewölkt
                </label>
                <label class="btn btn-outline-secondary @if(old('weather', optional($inspectionReport)->weather) == 'rainy') active @endif">
                    <input type="radio" name="weather" id="rainy" value="rainy" autocomplete="off" @if(old('weather', optional($inspectionReport)->weather) == 'rainy') checked @endif> regnerisch
                </label>
                <label class="btn btn-outline-secondary @if(old('weather', optional($inspectionReport)->weather) == 'snowy') active @endif">
                    <input type="radio" name="weather" id="snowy" value="snowy" autocomplete="off" @if(old('weather', optional($inspectionReport)->weather) == 'snowy') checked @endif> Schnee
                </label>
            </div>
            <div class="invalid-feedback @error('weather') d-block @enderror">
                @error('weather')
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
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>
            </svg>
            UVC Strahler
        </p>
        <p class="text-muted">
            Angaben zum UVC Strahler.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
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

        <div class="form-group">
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

        <div class="form-group">
            <label for="uvc_lamp_operating_hours">Betriebsstunden</label>
            <div class="input-group">
                <input type="number" min="0" step="1" class="form-control @error('uvc_lamp_operating_hours') is-invalid @enderror" id="uvc_lamp_operating_hours" name="uvc_lamp_operating_hours" placeholder="6800" value="{{ old('uvc_lamp_operating_hours', optional($inspectionReport)->uvc_lamp_operating_hours) }}" required />
                <div class="input-group-append">
                    <span class="input-group-text">h</span>
                </div>
                <div class="invalid-feedback">
                    @error('uvc_lamp_operating_hours')
                        {{ $message }}
                    @else
                        Gib bitte die Betriebsstunden der UVC Strahler ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
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

        <div class="form-group">
            <label for="uvc_lamp_uv_intensity_arrival">UV Intensität bei Ankunft</label>
            <div class="input-group">
                <input type="number" min="0" step="any" class="form-control @error('uvc_lamp_uv_intensity_arrival') is-invalid @enderror" id="uvc_lamp_uv_intensity_arrival" name="uvc_lamp_uv_intensity_arrival" placeholder="73" value="{{ old('uvc_lamp_uv_intensity_arrival', optional($inspectionReport)->uvc_lamp_uv_intensity_arrival) }}" required />
                <div class="input-group-append">
                    <span class="input-group-text">% oder W/m²</span>
                </div>
                <div class="invalid-feedback">
                    @error('uvc_lamp_uv_intensity_arrival')
                        {{ $message }}
                    @else
                        Gib bitte die UV Intensität der UVC Strahler bei Ankunft ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="uvc_lamp_uv_intensity_departure">UV Intensität bei Abfahrt</label>
            <div class="input-group">
                <input type="number" min="0" step="any" class="form-control @error('uvc_lamp_uv_intensity_departure') is-invalid @enderror" id="uvc_lamp_uv_intensity_departure" name="uvc_lamp_uv_intensity_departure" placeholder="103" value="{{ old('uvc_lamp_uv_intensity_departure', optional($inspectionReport)->uvc_lamp_uv_intensity_departure) }}" required />
                <div class="input-group-append">
                    <span class="input-group-text">% oder W/m²</span>
                </div>
                <div class="invalid-feedback">
                    @error('uvc_lamp_uv_intensity_departure')
                        {{ $message }}
                    @else
                        Gib bitte die UV Intensität der UVC Strahler bei Abfahrt ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="uvc_lamp_values_unit">Angaben der UV Intensitäten in</label>
            </div>
            <div class="btn-group btn-group-toggle @error('uvc_lamp_values_unit') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('uvc_lamp_values_unit', optional($inspectionReport)->uvc_lamp_values_unit) == 'percent') active @endif">
                    <input type="radio" name="uvc_lamp_values_unit" id="percent" value="percent" autocomplete="off" @if(old('uvc_lamp_values_unit', optional($inspectionReport)->uvc_lamp_values_unit) == 'percent') checked @endif> %
                </label>
                <label class="btn btn-outline-secondary @if(old('uvc_lamp_values_unit', optional($inspectionReport)->uvc_lamp_values_unit) == 'W_m2') active @endif">
                    <input type="radio" name="uvc_lamp_values_unit" id="W_m2" value="W_m2" autocomplete="off" @if(old('uvc_lamp_values_unit', optional($inspectionReport)->uvc_lamp_values_unit) == 'W_m2') checked @endif> W/m²
                </label>
            </div>
            <div class="invalid-feedback @error('uvc_lamp_values_unit') d-block @enderror">
                @error('uvc_lamp_values_unit')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="uvc_lamp_replacement_available">Ersatzstrahler vorhanden</label>
            </div>
            <div class="btn-group btn-group-toggle @error('uvc_lamp_replacement_available') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('uvc_lamp_replacement_available') === '1' || ($inspectionReport && $inspectionReport->uvc_lamp_replacement_available === true)) active @endif">
                    <input type="radio" name="uvc_lamp_replacement_available" id=1 value=1 autocomplete="off" @if(old('uvc_lamp_replacement_available') === '1' || ($inspectionReport && $inspectionReport->uvc_lamp_replacement_available === true)) checked @endif> ja
                </label>
                <label class="btn btn-outline-secondary @if(old('uvc_lamp_replacement_available') === '0' || ($inspectionReport && $inspectionReport->uvc_lamp_replacement_available === false)) active @endif">
                    <input type="radio" name="uvc_lamp_replacement_available" id=0 value=0 autocomplete="off" @if(old('uvc_lamp_replacement_available') === '0' || ($inspectionReport && $inspectionReport->uvc_lamp_replacement_available === false)) checked @endif> nein
                </label>
                <label class="btn btn-outline-secondary @if((old('_token') && old('uvc_lamp_replacement_available') === null) || ($inspectionReport && $inspectionReport->uvc_lamp_replacement_available === null)) active @endif">
                    <input type="radio" name="uvc_lamp_replacement_available" id="" value="" autocomplete="off" @if((old('_token') && old('uvc_lamp_replacement_available') === null) || ($inspectionReport && $inspectionReport->uvc_lamp_replacement_available === null)) checked @endif> keine Angabe
                </label>
            </div>
            <div class="invalid-feedback @error('uvc_lamp_replacement_available') d-block @enderror">
                @error('uvc_lamp_replacement_available')
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
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#activity"></use>
            </svg>
            UVC Sensor
        </p>
        <p class="text-muted">
            Angaben zum UVC Sensor.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
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

        <div class="form-group">
            <label for="uvc_sensor_identifier">Seriennummer</label>
            <input type="text" class="form-control @error('uvc_sensor_identifier') is-invalid @enderror" id="uvc_sensor_identifier" name="uvc_sensor_identifier" placeholder="1234abc89def" value="{{ old('uvc_sensor_identifier', optional($inspectionReport)->uvc_sensor_identifier) }}" required />
            <div class="invalid-feedback">
                @error('uvc_sensor_identifier')
                    {{ $message }}
                @else
                    Gib bitte den Typ des UVC Sensors ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="uvc_sensor_pre_alarm">Voralarm</label>
            <div class="input-group">
                <input type="number" min="0" step="any" class="form-control @error('uvc_sensor_pre_alarm') is-invalid @enderror" id="uvc_sensor_pre_alarm" name="uvc_sensor_pre_alarm" placeholder="68" value="{{ old('uvc_sensor_pre_alarm', optional($inspectionReport)->uvc_sensor_pre_alarm) }}" required />
                <div class="input-group-append">
                    <span class="input-group-text">% oder W/m²</span>
                </div>
                <div class="invalid-feedback">
                    @error('uvc_sensor_pre_alarm')
                        {{ $message }}
                    @else
                        Gib bitte den Voralarm des UVC Sensors ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="uvc_sensor_cut_off_point">Abschaltpunkt</label>
            <div class="input-group">
                <input type="number" min="0" step="any" class="form-control @error('uvc_sensor_cut_off_point') is-invalid @enderror" id="uvc_sensor_cut_off_point" name="uvc_sensor_cut_off_point" placeholder="62.9" value="{{ old('uvc_sensor_cut_off_point', optional($inspectionReport)->uvc_sensor_cut_off_point) }}" required />
                <div class="input-group-append">
                    <span class="input-group-text">% oder W/m²</span>
                </div>
                <div class="invalid-feedback">
                    @error('uvc_sensor_cut_off_point')
                    {{ $message }}
                    @else
                        Gib bitte den Abschaltpunkt des UVC Sensors ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="uvc_sensor_values_unit">Angaben der Werte in</label>
            </div>
            <div class="btn-group btn-group-toggle @error('uvc_sensor_values_unit') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('uvc_sensor_values_unit', optional($inspectionReport)->uvc_sensor_values_unit) == 'percent') active @endif">
                    <input type="radio" name="uvc_sensor_values_unit" id="percent" value="percent" autocomplete="off" @if(old('uvc_sensor_values_unit', optional($inspectionReport)->uvc_sensor_values_unit) == 'percent') checked @endif> %
                </label>
                <label class="btn btn-outline-secondary @if(old('uvc_sensor_values_unit', optional($inspectionReport)->uvc_sensor_values_unit) == 'W_m2') active @endif">
                    <input type="radio" name="uvc_sensor_values_unit" id="W_m2" value="W_m2" autocomplete="off" @if(old('uvc_sensor_values_unit', optional($inspectionReport)->uvc_sensor_values_unit) == 'W_m2') checked @endif> W/m²
                </label>
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
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#circle"></use>
            </svg>
            Überprüfung der Quartzschutzrohre
        </p>
        <p class="text-muted">
            Angaben zum Zustand der Quartzschutzrohre.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div>
                <label for="quartz_tube_contaminated">Verschmutzung</label>
            </div>
            <div class="btn-group btn-group-toggle @error('quartz_tube_contaminated') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('quartz_tube_contaminated') === '1' || ($inspectionReport && $inspectionReport->quartz_tube_contaminated === true)) active @endif">
                    <input type="radio" name="quartz_tube_contaminated" id=1 value=1 autocomplete="off" @if(old('quartz_tube_contaminated') === '1' || ($inspectionReport && $inspectionReport->quartz_tube_contaminated === true)) checked @endif> ja
                </label>
                <label class="btn btn-outline-secondary @if(old('quartz_tube_contaminated') === '0' || ($inspectionReport && $inspectionReport->quartz_tube_contaminated === false)) active @endif">
                    <input type="radio" name="quartz_tube_contaminated" id=0 value=0 autocomplete="off" @if(old('quartz_tube_contaminated') === '0' || ($inspectionReport && $inspectionReport->quartz_tube_contaminated === false)) checked @endif> nein
                </label>
                <label class="btn btn-outline-secondary @if((old('_token') && old('quartz_tube_contaminated') === null) || ($inspectionReport && $inspectionReport->quartz_tube_contaminated === null)) active @endif">
                    <input type="radio" name="quartz_tube_contaminated" id="" value="" autocomplete="off" @if((old('_token') && old('quartz_tube_contaminated') === null) || ($inspectionReport && $inspectionReport->quartz_tube_contaminated === null)) checked @endif> keine Angabe
                </label>
            </div>
            <div class="invalid-feedback @error('quartz_tube_contaminated') d-block @enderror">
                @error('quartz_tube_contaminated')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="quartz_tube_leaking">Undicht</label>
            </div>
            <div class="btn-group btn-group-toggle @error('quartz_tube_leaking') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('quartz_tube_leaking') === '1' || ($inspectionReport && $inspectionReport->quartz_tube_leaking === true)) active @endif">
                    <input type="radio" name="quartz_tube_leaking" id=1 value=1 autocomplete="off" @if(old('quartz_tube_leaking') === '1' || ($inspectionReport && $inspectionReport->quartz_tube_leaking === true)) checked @endif> ja
                </label>
                <label class="btn btn-outline-secondary @if(old('quartz_tube_leaking') === '0' || ($inspectionReport && $inspectionReport->quartz_tube_leaking === false)) active @endif">
                    <input type="radio" name="quartz_tube_leaking" id=0 value=0 autocomplete="off" @if(old('quartz_tube_leaking') === '0' || ($inspectionReport && $inspectionReport->quartz_tube_leaking === false)) checked @endif> nein
                </label>
                <label class="btn btn-outline-secondary @if((old('_token') && old('quartz_tube_leaking') === null) || ($inspectionReport && $inspectionReport->quartz_tube_leaking === null)) active @endif">
                    <input type="radio" name="quartz_tube_leaking" id="" value="" autocomplete="off" @if((old('_token') && old('quartz_tube_leaking') === null) || ($inspectionReport && $inspectionReport->quartz_tube_leaking === null)) checked @endif> keine Angabe
                </label>
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
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#droplet"></use>
            </svg>
            Wasser
        </p>
        <p class="text-muted">
            Angaben zum Zustand des Wassers.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="water_flow_rate">Durchfluss</label>
            <div class="input-group">
                <input type="number" min="0" step="any" class="form-control @error('water_flow_rate') is-invalid @enderror" id="water_flow_rate" name="water_flow_rate" placeholder="10" value="{{ old('water_flow_rate', optional($inspectionReport)->water_flow_rate) }}" required />
                <div class="input-group-append">
                    <span class="input-group-text">m³/h</span>
                </div>
                <div class="invalid-feedback">
                    @error('water_flow_rate')
                        {{ $message }}
                    @else
                        Gib bitte den Durchfluss des Wassers ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="water_minimum_uv_transmission">minimale Transmission[100mm]</label>
            <div class="input-group">
                <input type="number" min="0" max="100" step="any" class="form-control @error('water_minimum_uv_transmission') is-invalid @enderror" id="water_minimum_uv_transmission" name="water_minimum_uv_transmission" placeholder="15" value="{{ old('water_minimum_uv_transmission', optional($inspectionReport)->water_minimum_uv_transmission) }}" required />
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
                <div class="invalid-feedback">
                    @error('water_minimum_uv_transmission')
                    {{ $message }}
                    @else
                        Gib bitte die minimale Transmission[100mm] des Wassers ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="water_measured_uv_transmission">gemessene Transmission[100mm]</label>
            <div class="input-group">
                <input type="number" min="0" max="100" step="any" class="form-control @error('water_measured_uv_transmission') is-invalid @enderror" id="water_measured_uv_transmission" name="water_measured_uv_transmission" placeholder="26" value="{{ old('water_measured_uv_transmission', optional($inspectionReport)->water_measured_uv_transmission) }}" required />
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
                <div class="invalid-feedback">
                    @error('water_measured_uv_transmission')
                    {{ $message }}
                    @else
                        Gib bitte die gemessene Transmission[100mm] des Wassers ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="water_suspended_load_visible">Schwebestoffe sichtbar</label>
            </div>
            <div class="btn-group btn-group-toggle @error('water_suspended_load_visible') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('water_suspended_load_visible') === '1' || ($inspectionReport && $inspectionReport->water_suspended_load_visible === true)) active @endif">
                    <input type="radio" name="water_suspended_load_visible" id=1 value=1 autocomplete="off" @if(old('water_suspended_load_visible') === '1' || ($inspectionReport && $inspectionReport->water_suspended_load_visible === true)) checked @endif> ja
                </label>
                <label class="btn btn-outline-secondary @if(old('water_suspended_load_visible') === '0' || ($inspectionReport && $inspectionReport->water_suspended_load_visible === false)) active @endif">
                    <input type="radio" name="water_suspended_load_visible" id=0 value=0 autocomplete="off" @if(old('water_suspended_load_visible') === '0' || ($inspectionReport && $inspectionReport->water_suspended_load_visible === false)) checked @endif> nein
                </label>
                <label class="btn btn-outline-secondary @if((old('_token') && old('water_suspended_load_visible') === null) || ($inspectionReport && $inspectionReport->water_suspended_load_visible === null)) active @endif">
                    <input type="radio" name="water_suspended_load_visible" id="" value="" autocomplete="off" @if((old('_token') && old('water_suspended_load_visible') === null) || ($inspectionReport && $inspectionReport->water_suspended_load_visible === null)) checked @endif> keine Angabe
                </label>
            </div>
            <div class="invalid-feedback @error('water_suspended_load_visible') d-block @enderror">
                @error('water_suspended_load_visible')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="water_air_bubble_free">Luftblasenfrei</label>
            </div>
            <div class="btn-group btn-group-toggle @error('water_air_bubble_free') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('water_air_bubble_free') === '1' || ($inspectionReport && $inspectionReport->water_air_bubble_free === true)) active @endif">
                    <input type="radio" name="water_air_bubble_free" id=1 value=1 autocomplete="off" @if(old('water_air_bubble_free') === '1' || ($inspectionReport && $inspectionReport->water_air_bubble_free === true)) checked @endif> ja
                </label>
                <label class="btn btn-outline-secondary @if(old('water_air_bubble_free') === '0' || ($inspectionReport && $inspectionReport->water_air_bubble_free === false)) active @endif">
                    <input type="radio" name="water_air_bubble_free" id=0 value=0 autocomplete="off" @if(old('water_air_bubble_free') === '0' || ($inspectionReport && $inspectionReport->water_air_bubble_free === false)) checked @endif> nein
                </label>
                <label class="btn btn-outline-secondary @if((old('_token') && old('water_air_bubble_free') === null) || ($inspectionReport && $inspectionReport->water_air_bubble_free === null)) active @endif">
                    <input type="radio" name="water_air_bubble_free" id="" value="" autocomplete="off" @if((old('_token') && old('water_air_bubble_free') === null) || ($inspectionReport && $inspectionReport->water_air_bubble_free === null)) checked @endif> keine Angabe
                </label>
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
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Durchgeführte Arbeiten und Bemerkungen
        </p>
        <p class="text-muted">
            Durchgeführte Arbeiten während der Überprüfung sowie sonstige Bemerkungen.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="comment">
                Durchgeführte Arbeiten und Bemerkungen
            </label>
            <markdown-editor name="comment" placeholder="Leistungsfortschritt"  value="{{ old('comment', optional($inspectionReport)->comment) }}" v-cloak></markdown-editor>
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
        <div class="form-group">
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
