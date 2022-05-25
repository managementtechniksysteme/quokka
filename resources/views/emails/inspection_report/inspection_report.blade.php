@component('mail::message')
# Prüfbericht Anlage {{ $inspectionReport->equipment_identifier }} (Kunde {{ $inspectionReport->project->company->name }}) vom {{ $inspectionReport->inspected_on }}

**Techniker:** {{ $inspectionReport->employee->person->name }}<br />
**Status:**
{{ trans($inspectionReport->status) }}
@switch($inspectionReport->status)
@case('new')
(erstellt am {{ $inspectionReport->created_at }})
@break
@case('signed')
am {{ $inspectionReport->signature()->created_at }}
@break
@case('finished')
am {{ $inspectionReport->updated_at }}
@break
@endswitch
<br />**Datum:** {{ $inspectionReport->inspected_on }}<br />
**Wetter:** {{ trans($inspectionReport->weather) }}<br />
**Anlagentyp:** {{ $inspectionReport->equipment_type }}<br />
**Anlagen-/Gerätenummer:** {{ $inspectionReport->equipment_identifier }}<br />

**UVC Strahler**<br />
**Anzahl, Typ:** {{ Number::toLocal($inspectionReport->uvc_lamp_quantity) }} x {{ $inspectionReport->uvc_lamp_type }}<br />
**Betriebsstunden:** {{ Number::toLocal($inspectionReport->uvc_lamp_operating_hours) }}h<br />
**Impulse:** {{ Number::toLocal($inspectionReport->uvc_lamp_impulses) }}<br />
**UV Intensität bei Ankunft, Abfahrt:**
{{ Number::toLocal($inspectionReport->uvc_lamp_uv_intensity_arrival) }}{{ $inspectionReport->uvc_lamp_values_unit_string }},
{{ Number::toLocal($inspectionReport->uvc_lamp_uv_intensity_departure) }}{{ $inspectionReport->uvc_lamp_values_unit_string }}<br />
**Ersatzstrahler vorhanden:** {{ $inspectionReport->uvc_lamp_replacement_available_string }}<br />

**UVC Sensor**<br />
**Typ:** {{ $inspectionReport->uvc_sensor_type }}<br />
**Seriennummer:** {{ $inspectionReport->uvc_sensor_identifier }}<br />
**Voralarm:** {{ Number::toLocal($inspectionReport->uvc_sensor_pre_alarm) }}{{ $inspectionReport->uvc_sensor_values_unit_string }}<br />
**Abschaltpunkt:** {{ Number::toLocal($inspectionReport->uvc_sensor_cut_off_point) }}{{ $inspectionReport->uvc_sensor_values_unit_string }}<br />

**Überprüfung der Quarzschutzrohre**<br />
**Verschmutzung:** {{ $inspectionReport->quartz_tube_contaminated_string }}<br />
**Undicht:** {{ $inspectionReport->quartz_tube_leaking_string }}<br />

**Wasser**<br />
**Durchfluss:** {{ Number::toLocal($inspectionReport->water_flow_rate) }} m³/h<br />
**minimale, gemessene Transmission[100mm]:**
{{ Number::toLocal($inspectionReport->water_minimum_uv_transmission) }}%,
{{ Number::toLocal($inspectionReport->water_measured_uv_transmission) }}%<br />
**Schwebestoffe sichtbar:** {{ $inspectionReport->water_suspended_load_visible_string }}<br />
**Luftblasenfrei:** {{ $inspectionReport->water_air_bubble_free_string }}<br />

**Durchgeführte Arbeiten und Bemerkungen**<br />
{!! $inspectionReport->comment !!}

@component('mail::button', ['url' => route('inspection-reports.show', $inspectionReport)])
    Prüfbericht in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ $inspectionReport->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
