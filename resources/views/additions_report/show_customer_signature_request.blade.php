@extends('layouts.app')

@section('content')
    @if($additionsReport)
        <div class="bg-gray-100 mt-0">
            <div class="container py-4">
                <h3>
                    <svg class="icon-bs icon-baseline text-muted mr-1">
                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                    </svg>
                    Regiebericht unterschreiben und herunterladen
                    <small class="text-muted">{{ $additionsReport->project->name }} #{{ $additionsReport->number }}</small>
                </h3>
            </div>
        </div>

        <div class="container my-4">
            <div class="row">
                <div class="col-md-8 col-lg">
                    <div class="row">
                        <div class="col-sm-5 col-md col-lg-4">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                                </svg>
                                Datum
                            </div>
                        </div>
                        <div class="col-sm-7 col-md col-lg-8">
                            {{ $additionsReport->services_provided_on }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5 col-md col-lg-4">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                                </svg>
                                Regiestunden
                            </div>
                        </div>
                        <div class="col-sm-7 col-md col-lg-8">
                            {{ Number::toLocal($additionsReport->hours) }}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-5 col-md col-lg-4">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    @switch($additionsReport->weather)
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
                        <div class="col-sm-7 col-md col-lg-8">
                            {{ __($additionsReport->weather) }}
                            ({{ $additionsReport->minimum_temperature }}@if($additionsReport->minimum_temperature !== $additionsReport->maximum_temperature) bis {{ $additionsReport->maximum_temperature }}@endif °C)
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-5 col-md col-lg-4">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="@if($additionsReport->isNew()) text-primary @elseif($additionsReport->isSigned()) text-warning @else text-success @endif icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#git-commit"></use>
                                </svg>
                                Status
                            </div>
                        </div>
                        <div class="col-sm-7 col-md col-lg-8">
                            {{ __($additionsReport->status) }}
                            @switch($additionsReport->status)
                                @case('new')
                                    @if($additionsReport->signatureRequest)
                                        (Anfrage zur Unterschrift gesendet am {{ $additionsReport->signatureRequest->created_at }})
                                    @else
                                        (erstellt am {{ $additionsReport->created_at }})
                                    @endif
                                    @break
                                @case('signed')
                                    am {{ $additionsReport->signature()->created_at }}
                                    @break
                                @case('finished')
                                    am {{ $additionsReport->updated_at }}
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-lg">
                    <div class="row">
                        <div class="col-sm-5 col-md-12 col-lg">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                </svg>
                                Personalstand
                            </div>
                        </div>
                        <div class="col-sm-7 col-md-12 col-lg">
                            @foreach($additionsReport->involvedEmployees as $employee)
                                {{ $employee->person->name }}
                                @unless($loop->last)
                                    <br />
                                @endunless
                            @endforeach
                        </div>
                    </div>
                    @if($additionsReport->presentPeople->count())
                        <div class="row mt-3">
                            <div class="col-sm-5 col-md-12 col-lg">
                                <div class="text-muted d-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                    </svg>
                                    Anwesende Personen
                                </div>
                            </div>
                            <div class="col-sm-7 col-md-12 col-lg">
                                @foreach($additionsReport->presentPeople as $person)
                                    {{ $person->name }}
                                    @unless($loop->last)
                                        <br />
                                    @endunless
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($additionsReport->other_visitors)
                        <div class="row mt-3">
                            <div class="col-sm-5 col-md-12 col-lg">
                                <div class="text-muted d-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                    </svg>
                                    Sonstige Besucher
                                </div>
                            </div>
                            <div class="col-sm-7 col-md-12 col-lg">
                                {{ $additionsReport->other_visitors }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($additionsReport->inspection_comment)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                    </svg>
                    Güte- und Funktionsprüfung
                </div>
                <p>{{ $additionsReport->inspection_comment }}</p>
            @endif

            @if ($additionsReport->missing_documents)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                    </svg>
                    Fehlende Ausführungsunterlagen
                </div>
                <p>{{ $additionsReport->missing_documents }}</p>
            @endif

            @if ($additionsReport->special_occurrences)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                    </svg>
                    Besondere Vorkommnisse
                </div>
                <p>{{ $additionsReport->special_occurrences }}</p>
            @endif

            @if ($additionsReport->imminent_danger)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-octagon"></use>
                    </svg>
                    Gefahr in Verzug
                </div>
                <p>{{ $additionsReport->imminent_danger }}</p>
            @endif

            @if ($additionsReport->concerns)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#flag"></use>
                    </svg>
                    Bedenken
                </div>
                <p>{{ $additionsReport->concerns }}</p>
            @endif

            @if ($additionsReport->comment)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                    </svg>
                    Leistungsfortschritt
                </div>
                <div class="markdown">
                    {!! Html::fromMarkdown($additionsReport->comment) !!}
                </div>
            @endif

            <div class="alert alert-info mt-4" role="alert">
                <div class="d-inline-flex align-items-center">
                    <svg class="icon icon-24 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                    </svg>
                    Der Regiebericht kann nach erfolgreicher Unterschrift heruntergeladen werden.
                </div>
            </div>

            <h4 class="mt-4">Regiebericht unterschreiben</h4>

            <p>Unterschreiben Sie bitte in folgendem Feld.<br />
                Am Computer unterschreiben Sie mid der Maus, indem Sie die linke Maustaste gedrückt halten. Am Mobiltelefon, Tablet oder anderen Geräten mit Touchscreen benutzen Sie Ihren Finger oder einen für ihr Gerät passenden Eingabestift.<br />
                Klicken Sie danach auf den <strong>Bautagesbericht unterschreiben</strong> Button. Mit dem <strong>Zurücksetzen</strong> Button können Sie die Eingabe löschen.</p>

            <form action="{{ route('additions-reports.customer-sign', $additionsReport->signatureRequest->token) }}" method="post">
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
                            Regiebericht unterschreiben
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
        @include('additions_report.sign_invalid_content')
    @endif
@endsection
