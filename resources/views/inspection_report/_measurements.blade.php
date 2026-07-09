{{-- Messwerte — shared by the internal show page and the customer signature page. --}}
<div class="row g-3">
    <div class="col-12 col-lg-6">
        <div class="q-card h-100">
            <div class="q-card__head d-flex align-items-center gap-2">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sun"></use></svg>
                UVC Strahler
            </div>
            <div class="q-card__body">
                <div class="q-spec">
                    <div class="q-spec__row">
                        <span class="q-spec__label">Anzahl, Typ</span>
                        <span class="q-spec__value">{{ $inspectionReport->uvc_lamp_quantity }} x {{ $inspectionReport->uvc_lamp_type }}</span>
                    </div>
                    <div class="q-spec__row">
                        <span class="q-spec__label">Betriebsstunden</span>
                        <span class="q-spec__value">{{ Number::toLocal($inspectionReport->uvc_lamp_operating_hours) }}h</span>
                    </div>
                    <div class="q-spec__row">
                        <span class="q-spec__label">Impulse</span>
                        <span class="q-spec__value">{{ Number::toLocal($inspectionReport->uvc_lamp_impulses) }}</span>
                    </div>
                    <div class="q-spec__row">
                        <span class="q-spec__label">UV Intensität bei Ankunft, Abfahrt</span>
                        <span class="q-spec__value">{{ Number::toLocal($inspectionReport->uvc_lamp_uv_intensity_arrival) }}{{ $inspectionReport->uvc_lamp_values_unit_string }}, {{ Number::toLocal($inspectionReport->uvc_lamp_uv_intensity_departure) }}{{ $inspectionReport->uvc_lamp_values_unit_string }}</span>
                    </div>
                    <div class="q-spec__row">
                        <span class="q-spec__label">Ersatzstrahler vorhanden</span>
                        <span class="q-spec__value">{{ $inspectionReport->uvc_lamp_replacement_available_string }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="q-card h-100">
            <div class="q-card__head d-flex align-items-center gap-2">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use></svg>
                UVC Sensor
            </div>
            <div class="q-card__body">
                <div class="q-spec">
                    <div class="q-spec__row">
                        <span class="q-spec__label">Typ</span>
                        <span class="q-spec__value">{{ $inspectionReport->uvc_sensor_type }}</span>
                    </div>
                    <div class="q-spec__row">
                        <span class="q-spec__label">Seriennummer</span>
                        <span class="q-spec__value">{{ $inspectionReport->uvc_sensor_identifier }}</span>
                    </div>
                    <div class="q-spec__row">
                        <span class="q-spec__label">Voralarm</span>
                        <span class="q-spec__value">{{ Number::toLocal($inspectionReport->uvc_sensor_pre_alarm) }}{{ $inspectionReport->uvc_sensor_values_unit_string }}</span>
                    </div>
                    <div class="q-spec__row">
                        <span class="q-spec__label">Abschaltpunkt</span>
                        <span class="q-spec__value">{{ Number::toLocal($inspectionReport->uvc_sensor_cut_off_point) }}{{ $inspectionReport->uvc_sensor_values_unit_string }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="q-card h-100">
            <div class="q-card__head d-flex align-items-center gap-2">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#droplet"></use></svg>
                Wasser
            </div>
            <div class="q-card__body">
                <div class="q-spec">
                    <div class="q-spec__row">
                        <span class="q-spec__label">Durchfluss</span>
                        <span class="q-spec__value">{{ Number::toLocal($inspectionReport->water_flow_rate) }} m³/h</span>
                    </div>
                    <div class="q-spec__row">
                        <span class="q-spec__label">Min., gem. Transmission [100mm]</span>
                        <span class="q-spec__value">{{ Number::toLocal($inspectionReport->water_minimum_uv_transmission) }}%, {{ Number::toLocal($inspectionReport->water_measured_uv_transmission) }}%</span>
                    </div>
                    <div class="q-spec__row">
                        <span class="q-spec__label">Schwebestoffe sichtbar</span>
                        <span class="q-spec__value">{{ $inspectionReport->water_suspended_load_visible_string }}</span>
                    </div>
                    <div class="q-spec__row">
                        <span class="q-spec__label">Luftblasenfrei</span>
                        <span class="q-spec__value">{{ $inspectionReport->water_air_bubble_free_string }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="q-card h-100">
            <div class="q-card__head d-flex align-items-center gap-2">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#circle"></use></svg>
                Überprüfung der Quarzschutzrohre
            </div>
            <div class="q-card__body">
                <div class="q-spec">
                    <div class="q-spec__row">
                        <span class="q-spec__label">Verschmutzung</span>
                        <span class="q-spec__value">{{ $inspectionReport->quartz_tube_contaminated_string }}</span>
                    </div>
                    <div class="q-spec__row">
                        <span class="q-spec__label">Undicht</span>
                        <span class="q-spec__value">{{ $inspectionReport->quartz_tube_leaking_string }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
