@extends('layouts.app')

@section('content')
    @if($flowMeterInspectionReport)
        <div class="bg-gray-100 mt-0">
            <div class="container py-4">
                <h3>
                    <svg class="icon-bs icon-baseline text-muted mr-1">
                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                    </svg>
                    Prüfbericht für Durchflussmesseinrichtungen unterschreiben und herunterladen
                    <small class="text-muted">Anlage {{ $flowMeterInspectionReport->equipment_identifier }} vom {{ $flowMeterInspectionReport->inspected_on }}</small>
                </h3>
            </div>
        </div>

        <div class="container my-4">

            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                        </svg>
                        Techniker
                    </div>
                </div>
                <div class="col">
                    {{ $flowMeterInspectionReport->employee->person->name }}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                        </svg>
                        Datum
                    </div>
                </div>
                <div class="col">
                    {{ $flowMeterInspectionReport->inspected_on }}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            @switch($flowMeterInspectionReport->weather)
                                @case('sunny')
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>
                                    @break
                                @case('cloudy')
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cloud"></use>
                                    @break
                                @case('rainy')
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cloud-rain"></use>
                                    @break
                                @case('snowy')
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cloud-snow"></use>
                                    @break
                            @endswitch
                        </svg>
                        Wetter
                    </div>
                </div>
                <div class="col">
                    {{ __($flowMeterInspectionReport->weather) }} ({{ $flowMeterInspectionReport->temperature }} °C)
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon-bs icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#building"></use>
                        </svg>
                        Anlage
                    </div>
                </div>
                <div class="col">
                    {{ $flowMeterInspectionReport->equipment_identifier }}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                        </svg>
                        Messstelle
                    </div>
                </div>
                <div class="col">
                    {{ $flowMeterInspectionReport->measuring_point }}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon-bs icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-left-right"></use>
                        </svg>
                        Abweichung Messwerte
                    </div>
                </div>
                <div class="col">
                    von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub>: {{ $flowMeterInspectionReport->measurement_difference_up_to_30_q_max }}, über 0,3 Q<sub>max</sub>: {{ $flowMeterInspectionReport->measurement_difference_above_30_q_max }}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-sm-3">
                    <div class="text-muted d-flex align-items-center">
                        <svg class="icon-bs icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-left-right"></use>
                        </svg>
                        Abweichung Zählerstände
                    </div>
                </div>
                <div class="col">
                    von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub>: {{ $flowMeterInspectionReport->reading_difference_up_to_30_q_max }}, über 0,3 Q<sub>max</sub>: {{ $flowMeterInspectionReport->reading_difference_above_30_q_max }}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <div class="d-flex align-items-center @if($flowMeterInspectionReport->equipment_in_tolerance_range) text-success @else text-danger @endif">
                        <svg class="icon icon-16 mr-2">
                            @if($flowMeterInspectionReport->equipment_in_tolerance_range)
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check"></use>
                            @else
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x"></use>
                            @endif
                        </svg>
                        Das Messystem arbeitet {{ $flowMeterInspectionReport->equipment_in_tolerance_range ? 'innerhalb' : 'außerhalb' }} des Toleranzbereichs des ÖWAV Regelblatts 38.
                    </div>
                </div>
            </div>

            @if($flowMeterInspectionReport->equipment_deficiencies)
                <div class="row mt-3">
                    <div class="col-sm-3">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                            </svg>
                            Festgestellte Mängel
                        </div>
                    </div>
                    <div class="col">
                        {{ $flowMeterInspectionReport->equipment_deficiencies }}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-3">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#repeat"></use>
                            </svg>
                            Zweitprüfung erforderlich
                        </div>
                    </div>
                    <div class="col">
                        {{ $flowMeterInspectionReport->further_inspection_required_string }}
                    </div>
                </div>
            @endif

            @if ($flowMeterInspectionReport->comment)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                    </svg>
                    Sonstige Bemerkungen
                </div>
                <div class="markdown">
                    {!! Html::fromMarkdown($flowMeterInspectionReport->comment) !!}
                </div>
            @endif

            @if ($flowMeterInspectionReport->comment)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                    </svg>
                    Sonstige Bemerkungen
                </div>
                <div class="markdown">
                    {!! Html::fromMarkdown($flowMeterInspectionReport->comment) !!}
                </div>
            @endif

            <div class="alert alert-info mt-4" role="alert">
                <div class="d-inline-flex align-items-center">
                    <svg class="icon icon-24 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                    </svg>
                    Der Prüfbericht kann nach erfolgreicher Unterschrift heruntergeladen werden.
                </div>
            </div>

            <h4 class="mt-4">Prüfbericht unterschreiben</h4>

            <p>Unterschreiben Sie bitte in folgendem Feld.<br />
                Am Computer unterschreiben Sie mid der Maus, indem Sie die linke Maustaste gedrückt halten. Am Mobiltelefon, Tablet oder anderen Geräten mit Touchscreen benutzen Sie Ihren Finger oder einen für ihr Gerät passenden Eingabestift.<br />
                Klicken Sie danach auf den <strong>Prüfbericht unterschreiben</strong> Button. Mit dem <strong>Zurücksetzen</strong> Button können Sie die Eingabe löschen.</p>

            <form action="{{ route('flow-meter-inspection-reports.customer-sign', $flowMeterInspectionReport->signatureRequest->token) }}" method="post">
                @csrf

                <signature-pad></signature-pad>
                <div class="invalid-feedback @error('signature') d-block @enderror">
                    @error('signature')
                    {{ $message }}
                    @enderror
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            Prüfbericht unterschreiben
                        </button>
                        <a class="btn btn-outline-secondary d-inline-flex align-items-center ml-1" href="">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Zurücksetzen
                        </a>
                    </div>
                </div>

            </form>
        </div>
    @else
        @include('flow_meter_inspection_report.sign_invalid_content')
    @endif
@endsection
