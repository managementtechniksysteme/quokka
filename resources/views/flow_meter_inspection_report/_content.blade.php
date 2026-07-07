{{-- Report cards (verdict banner is separate: _verdict, rendered full-width) —
     shared by the internal show page and the customer signature page. --}}
{{-- Abweichungen --}}
<div class="q-card">
    <div class="q-card__head">Abweichungen</div>
    <div class="q-card__body">
        <div class="q-spec">
            <div class="q-spec__row">
                <span class="q-spec__label">Abweichung Messwerte</span>
                <span class="q-spec__value">von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub>: {{ $flowMeterInspectionReport->measurement_difference_up_to_30_q_max }}, über 0,3 Q<sub>max</sub>: {{ $flowMeterInspectionReport->measurement_difference_above_30_q_max }}</span>
            </div>
            <div class="q-spec__row">
                <span class="q-spec__label">Abweichung Zählerstände</span>
                <span class="q-spec__value">von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub>: {{ $flowMeterInspectionReport->reading_difference_up_to_30_q_max }}, über 0,3 Q<sub>max</sub>: {{ $flowMeterInspectionReport->reading_difference_above_30_q_max }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Mängel --}}
@if($flowMeterInspectionReport->equipment_deficiencies)
    <div class="q-card">
        <div class="q-card__head">Mängel</div>
        <div class="q-card__body d-flex flex-column gap-3">
            <div>
                <div class="q-section-label">Festgestellte Mängel</div>
                <div>{{ $flowMeterInspectionReport->equipment_deficiencies }}</div>
            </div>
            <div>
                <div class="q-section-label">Zweitprüfung erforderlich</div>
                <div>{{ $flowMeterInspectionReport->further_inspection_required_string }}</div>
            </div>
        </div>
    </div>
@endif

{{-- Sonstige Bemerkungen --}}
@if ($flowMeterInspectionReport->comment)
    <div class="q-card">
        <div class="q-card__head">Sonstige Bemerkungen</div>
        <div class="q-card__body">
            <div class="markdown">
                {!! Html::fromMarkdown($flowMeterInspectionReport->comment) !!}
            </div>
        </div>
    </div>
@endif
