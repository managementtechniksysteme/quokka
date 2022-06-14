@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('construction_report.breadcrumb')

            <h3>
                Bautagesbericht
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $constructionReport->project->name }} #{{ $constructionReport->number }}
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @unless($constructionReport->isFinished())
                    @can('approve', $constructionReport)
                        <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('construction-reports.finish', ['construction_report' => $constructionReport, 'redirect' => 'show']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            Erledigen
                        </a>
                    @endcan
                @endunless
                @can('update', $constructionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('construction-reports.edit', $constructionReport) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('email', $constructionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('construction-reports.email', ['construction_report' => $constructionReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $constructionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('construction-reports.download', $constructionReport) }}" target="_blank">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                @endcan
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                @can('sign', $constructionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('construction-reports.sign', ['construction_report' => $constructionReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                        </svg>
                        Unterschreiben lassen
                    </a>
                @endcan
                @can('emailSignatureRequest', $constructionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('construction-reports.email-signature-request', ['construction_report' => $constructionReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Unterschrift Anfrage senden
                    </a>
                @endcan
                @can('emailDownloadRequest', $constructionReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('construction-reports.email-download-request', ['construction_report' => $constructionReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use>
                        </svg>
                        Download Link senden
                    </a>
                @endcan
                @can('delete', $constructionReport)
                    <form action="{{ route('construction-reports.destroy', $constructionReport) }}" method="post" >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                @endcan
            </div>

        </div>
    </div>

    <div class="container my-4">
        <div class="row">
            <div class="col-sm-5 col-md-4 col-lg-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                    </svg>
                    Bauvorhaben
                </div>
            </div>
            <div class="col-sm-7">
                <a href="{{ route('projects.show', $constructionReport->project) }}">{{ $constructionReport->project->name }}</a>
            </div>
        </div>

        <div class="row mt-3 mt-md-4">
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
                <div class="row mt-3 mt-md-0">
                    <div class="col-sm-5 col-md-12 col-lg">
                        <div class="text-muted d-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                            </svg>
                            Ersteller
                        </div>
                    </div>
                    <div class="col-sm-7 col-md-12 col-lg">
                        {{ $constructionReport->employee->person->name }}
                    </div>
                </div>
                <div class="row mt-3">
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

        @if($constructionReport->attachments()->count() > 0)
            <div class="row text-muted d-flex align-items-center mt-1">
                <div class="col">
                    <div class="d-none d-md-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                        </svg>
                        Anhänge
                    </div>
                    <a class="d-inline-flex d-md-none d-inline-flex align-items-center" data-toggle="collapse" href="#collapseConstructionReportAttachments-{{ $constructionReport->id }}" role="button" aria-expanded="false" aria-controls="collapseConstructionReportAttachments-{{ $constructionReport->id }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                        </svg>
                        Anhänge
                    </a>
                </div>
            </div>
            <div class="d-none d-md-block">
                <div class="row">
                    @foreach($constructionReport->attachments() as $attachment)
                        <div class="col-12 col-md-6 col-lg-3 mt-1">
                            <div class="attachment bg-gray-100 border border-gray-300 d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                                @if($attachment->hasGeneratedConversion('thumbnail'))
                                    <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                @else
                                    <svg class="icon attachment-img-preview mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use>
                                    </svg>
                                @endif
                                <div class="min-w-0">
                                    <div class="min-w-0 text-truncate">{{ $attachment->file_name }}</div>
                                    <div class="text-muted">{{ $attachment->human_readable_size }}</div>
                                </div>
                                <a href="{{ $attachment->getUrl() }}" class="stretched-link outline-none"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="collapse d-md-none" id="collapseConstructionReportAttachments-{{ $constructionReport->id }}">
                <div class="row">
                    @foreach($constructionReport->attachments() as $attachment)
                        <div class="col-12 col-md-6 col-lg-3 mt-1">
                            <div class="attachment bg-gray-100 border border-gray-300 d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                                @if($attachment->hasGeneratedConversion('thumbnail'))
                                    <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                @else
                                    <svg class="icon attachment-img-preview mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use>
                                    </svg>
                                @endif
                                <div class="min-w-0">
                                    <div class="min-w-0 text-truncate">{{ $attachment->file_name }}</div>
                                    <div class="text-muted">{{ $attachment->human_readable_size }}</div>
                                </div>
                                <a href="{{ $attachment->getUrl() }}" class="stretched-link outline-none"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

@endsection
