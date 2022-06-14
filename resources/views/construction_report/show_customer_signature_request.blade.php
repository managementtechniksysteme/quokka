@extends('layouts.app')

@section('content')
    @if($constructionReport)
        <div class="bg-gray-100 mt-0">
            <div class="container py-4">
                <h3>
                    <svg class="icon-bs icon-baseline text-muted mr-1">
                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
                    </svg>
                    Bautagesbericht unterschreiben und herunterladen
                    <small class="text-muted">{{ $constructionReport->project->name }} #{{ $constructionReport->number }}</small>
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
                            {{ $constructionReport->services_provided_on }}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-5 col-md col-lg-4">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    @switch($constructionReport->weather)
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
                            {{ __($constructionReport->weather) }}
                            ({{ $constructionReport->minimum_temperature }}@if($constructionReport->minimum_temperature !== $constructionReport->maximum_temperature) bis {{ $constructionReport->maximum_temperature }}@endif °C)
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-5 col-md col-lg-4">
                            <div class="text-muted d-flex align-items-center">
                                <svg class="@if($constructionReport->isNew()) text-primary @elseif($constructionReport->isSigned()) text-warning @else text-success @endif icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#git-commit"></use>
                                </svg>
                                Status
                            </div>
                        </div>
                        <div class="col-sm-7 col-md col-lg-8">
                            {{ __($constructionReport->status) }}
                            @switch($constructionReport->status)
                                @case('new')
                                    @if($constructionReport->signatureRequest)
                                        (Anfrage zur Unterschrift gesendet am {{ $constructionReport->signatureRequest->created_at }})
                                    @else
                                        (erstellt am {{ $constructionReport->created_at }})
                                    @endif
                                    @break
                                @case('signed')
                                    am {{ $constructionReport->signature()->created_at }}
                                    @break
                                @case('finished')
                                    am {{ $constructionReport->updated_at }}
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
                            @foreach($constructionReport->involvedEmployees as $employee)
                                {{ $employee->person->name }}
                                @unless($loop->last)
                                    <br />
                                @endunless
                            @endforeach
                        </div>
                    </div>
                    @if($constructionReport->presentPeople->count())
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
                                @foreach($constructionReport->presentPeople as $person)
                                    {{ $person->name }}
                                    @unless($loop->last)
                                        <br />
                                    @endunless
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($constructionReport->other_visitors)
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
                                {{ $constructionReport->other_visitors }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($constructionReport->inspection_comment)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                    </svg>
                    Güte- und Funktionsprüfung
                </div>
                <p>{{ $constructionReport->inspection_comment }}</p>
            @endif

            @if ($constructionReport->missing_documents)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                    </svg>
                    Fehlende Ausführungsunterlagen
                </div>
                <p>{{ $constructionReport->missing_documents }}</p>
            @endif

            @if ($constructionReport->special_occurrences)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                    </svg>
                    Besondere Vorkommnisse
                </div>
                <p>{{ $constructionReport->special_occurrences }}</p>
            @endif

            @if ($constructionReport->imminent_danger)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-octagon"></use>
                    </svg>
                    Gefahr in Verzug
                </div>
                <p>{{ $constructionReport->imminent_danger }}</p>
            @endif

            @if ($constructionReport->concerns)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#flag"></use>
                    </svg>
                    Bedenken
                </div>
                <p>{{ $constructionReport->concerns }}</p>
            @endif

            @if ($constructionReport->comment)
                <div class="text-muted d-flex align-items-center mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                    </svg>
                    Leistungsfortschritt
                </div>
                <div class="markdown">
                    {!! Html::fromMarkdown($constructionReport->comment) !!}
                </div>
            @endif

            <div class="alert alert-info mt-4" role="alert">
                <div class="d-inline-flex align-items-center">
                    <svg class="icon icon-24 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                    </svg>
                    Der Bautagesbericht kann nach erfolgreicher Unterschrift heruntergeladen werden.
                </div>
            </div>

            <h4 class="mt-4">Bautagesbericht unterschreiben</h4>

            <p>Unterschreiben Sie bitte in folgendem Feld.<br />
                Am Computer unterschreiben Sie mid der Maus, indem Sie die linke Maustaste gedrückt halten. Am Mobiltelefon, Tablet oder anderen Geräten mit Touchscreen benutzen Sie Ihren Finger oder einen für ihr Gerät passenden Eingabestift.<br />
                Klicken Sie danach auf den <strong>Bautagesbericht unterschreiben</strong> Button. Mit dem <strong>Zurücksetzen</strong> Button können Sie die Eingabe löschen.</p>

            <form action="{{ route('construction-reports.customer-sign', $constructionReport->signatureRequest->token) }}" method="post">
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
                            Bautagesbericht unterschreiben
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
        @include('construction_report.sign_invalid_content')
    @endif
@endsection
