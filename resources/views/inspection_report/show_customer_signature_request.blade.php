@extends('layouts.app')

@section('content')
    @if($inspectionReport)
        <div class="bg-gray-100 mt-0">
            <div class="container py-4">
                <h3>
                    Prüfbericht unterschreiben und herunterladen
                    <small class="text-muted">Anlage {{ $inspectionReport->equipment_identifier }} vom {{ $inspectionReport->inspected_on }}</small>
                </h3>
            </div>
        </div>

        <div class="container my-4">
            <div class="row">
                <div class="col-sm">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                                </svg>
                                Techniker
                            </div>
                        </div>
                        <div class="col-lg-6">
                            {{ $inspectionReport->employee->person->name }}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                                </svg>
                                Datum
                            </div>
                        </div>
                        <div class="col-lg-6">
                            {{ $inspectionReport->inspected_on }}
                        </div>
                    </div>
                </div>

                <div class="col-sm mt-3 mt-sm-0">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    @switch($inspectionReport->weather)
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
                        <div class="col-lg-6">
                            {{ __($inspectionReport->weather) }}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#list"></use>
                                </svg>
                                Anlagentyp
                            </div>
                        </div>
                        <div class="col-lg-6">
                            {{ $inspectionReport->equipment_type }}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#hash"></use>
                                </svg>
                                Anlagen-/Gerätenummer
                            </div>
                        </div>
                        <div class="col-lg-6">
                            {{ $inspectionReport->equipment_identifier }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-sm">
                    <div class="row">
                        <div class="col">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>
                                </svg>
                                <span class="font-weight-bolder">UVC Strahler</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            Anzahl, Typ
                        </div>
                        <div class="col-lg-6">
                            {{ $inspectionReport->uvc_lamp_quantity }} x {{ $inspectionReport->uvc_lamp_type }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            Betriebsstunden
                        </div>
                        <div class="col-lg-6">
                            {{ Number::toLocal($inspectionReport->uvc_lamp_operating_hours) }}h
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            Impulse
                        </div>
                        <div class="col-lg-6">
                            {{ Number::toLocal($inspectionReport->uvc_lamp_impulses) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            UV Intensität bei Ankunft, Abfahrt
                        </div>
                        <div class="col-lg-6">
                            {{ Number::toLocal($inspectionReport->uvc_lamp_uv_intensity_arrival) }}{{ $inspectionReport->uvc_lamp_values_unit_string }},
                            {{ Number::toLocal($inspectionReport->uvc_lamp_uv_intensity_departure) }}{{ $inspectionReport->uvc_lamp_values_unit_string }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            Ersatzstrahler vorhanden
                        </div>
                        <div class="col-lg-6">
                            {{ $inspectionReport->uvc_lamp_replacement_available_string }}
                        </div>
                    </div>
                </div>
                <div class="col-sm mt-4 mt-sm-0">
                    <div class="row">
                        <div class="col">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#activity"></use>
                                </svg>
                                <span class="font-weight-bolder">UVC Sensor</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            Typ
                        </div>
                        <div class="col-lg-6">
                            {{ $inspectionReport->uvc_sensor_type }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            Seriennummer
                        </div>
                        <div class="col-lg-6">
                            {{ $inspectionReport->uvc_sensor_identifier }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            Voralarm
                        </div>
                        <div class="col-lg-6">
                            {{ Number::toLocal($inspectionReport->uvc_sensor_pre_alarm) }}{{ $inspectionReport->uvc_sensor_values_unit_string }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            Abschaltpunkt
                        </div>
                        <div class="col-lg-6">
                            {{ Number::toLocal($inspectionReport->uvc_sensor_cut_off_point) }}{{ $inspectionReport->uvc_sensor_values_unit_string }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-sm">
                    <div class="row">
                        <div class="col">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#droplet"></use>
                                </svg>
                                <span class="font-weight-bolder">Wasser</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            Durchfluss
                        </div>
                        <div class="col-lg-6">
                            {{ Number::toLocal($inspectionReport->water_flow_rate) }} m³/h
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            min<span class="d-lg-none">imale</span><span class="d-none d-lg-inline">.</span>, gem<span class="d-lg-none">essene</span><span class="d-none d-lg-inline">.</span> Trans<span class="d-lg-none d-xl-inline">mission</span><span class="d-none d-lg-inline d-xl-none">. </span>[100mm]
                        </div>
                        <div class="col-lg-6">
                            {{ Number::toLocal($inspectionReport->water_minimum_uv_transmission) }}%,
                            {{ Number::toLocal($inspectionReport->water_measured_uv_transmission) }}%
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            Schwebestoffe sichtbar
                        </div>
                        <div class="col-lg-6">
                            {{ $inspectionReport->water_suspended_load_visible_string }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            Luftblasenfrei
                        </div>
                        <div class="col-lg-6">
                            {{ $inspectionReport->water_air_bubble_free_string }}
                        </div>
                    </div>
                </div>
                <div class="col-sm mt-4 mt-sm-0">
                    <div class="row">
                        <div class="col">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#circle"></use>
                                </svg>
                                <span class="font-weight-bolder"><span class="d-inline d-sm-none d-md-inline">Überprüfung der </span>Quarzschutzrohre</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            Verschmutzung
                        </div>
                        <div class="col-lg-6">
                            {{ $inspectionReport->quartz_tube_contaminated_string }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 text-muted">
                            Undicht
                        </div>
                        <div class="col-lg-6">
                            {{ $inspectionReport->quartz_tube_leaking_string }}
                        </div>
                    </div>
                </div>
            </div>

            @if ($inspectionReport->comment)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                    </svg>
                    Durchgeführte Arbeiten und Bemerkungen
                </div>
                {!! Html::fromMarkdown($inspectionReport->comment) !!}
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

            <form action="{{ route('inspection-reports.customer-sign', $inspectionReport->signatureRequest->token) }}" method="post">
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
        @include('inspection_report.sign_invalid_content')
    @endif
@endsection
