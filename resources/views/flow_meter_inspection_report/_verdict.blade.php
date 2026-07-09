{{-- Toleranzbereich-Verdikt — shared by show + customer page (rendered full-width). --}}
<div class="q-banner {{ $flowMeterInspectionReport->equipment_in_tolerance_range ? 'q-banner--success' : 'q-banner--danger' }} mb-0">
    <svg class="icon-bs icon-16">
        <use href="{{ asset('svg/bootstrap-icons.svg') }}#{{ $flowMeterInspectionReport->equipment_in_tolerance_range ? 'check' : 'x' }}"></use>
    </svg>
    <span>Das Messsystem arbeitet {{ $flowMeterInspectionReport->equipment_in_tolerance_range ? 'innerhalb' : 'außerhalb' }} des Toleranzbereichs des ÖWAV Regelblatts 38.</span>
</div>
