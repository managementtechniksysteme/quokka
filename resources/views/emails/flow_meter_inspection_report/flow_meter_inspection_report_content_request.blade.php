# Zusammenfassung des Prüfberichtes

**Techniker:** {{ $flowMeterInspectionReport->employee->person->name }}<br />
**Status:**
{{ trans($flowMeterInspectionReport->status) }}
@switch($flowMeterInspectionReport->status)
@case('new')
(erstellt am {{ $flowMeterInspectionReport->created_at }})
@break
@case('signed')
am {{ $flowMeterInspectionReport->signature()->created_at }}
@break
@case('finished')
am {{ $flowMeterInspectionReport->updated_at }}
@break
@endswitch
<br />**Datum:** {{ $flowMeterInspectionReport->inspected_on }}<br />
**Wetter:** {{ trans($flowMeterInspectionReport->weather) }} ({{ $flowMeterInspectionReport->temperature }} °C)<br />
**Anlage:** {{ $flowMeterInspectionReport->equipment_identifier }}<br />
**Messstelle:** {{ $flowMeterInspectionReport->measuring_point }}<br />
**Abweichung der Messwerte von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub>:** {{ $flowMeterInspectionReport->measurement_difference_up_to_30_q_max }}<br />
**Abweichung der Messwerte über 0,3 Q<sub>max</sub>:** {{ $flowMeterInspectionReport->measurement_difference_above_30_q_max }}<br />
**Abweichung der Zählerstände von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub>:** {{ $flowMeterInspectionReport->reading_difference_up_to_30_q_max }}<br />
**Abweichung der Zählerstände über 0,3 Q<sub>max</sub>:** {{ $flowMeterInspectionReport->reading_difference_above_30_q_max }}<br />

Das Messsystem arbeitet **{{ $flowMeterInspectionReport->equipment_in_tolerance_range ? 'innerhalb' : 'außerhalb' }}** des Toleranzbereichs des ÖWAV Regelblatts 38.

@if($flowMeterInspectionReport->equipment_deficiencies)
**Festgestellte Mängel:** {{ $flowMeterInspectionReport->equipment_deficiencies }}<br />
**Zweitprüfung/Vollprüfung nach Korrektur erforderlich:** {{ $flowMeterInspectionReport->further_inspection_required_string }}<br />
@endif

**Sonstige Bemerkungen**<br />
{!! $flowMeterInspectionReport->comment !!}
