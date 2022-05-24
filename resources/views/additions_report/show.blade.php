@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('additions_report.breadcrumb')

            <h3>
                Regiebericht
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $additionsReport->project->name }} #{{ $additionsReport->number }}
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @unless($additionsReport->isFinished())
                    @can('approve', $additionsReport)
                        <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('additions-reports.finish', ['additions_report' => $additionsReport, 'redirect' => 'show']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            Erledigen
                        </a>
                    @endcan
                @endunless
                @can('update', $additionsReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('additions-reports.edit', $additionsReport) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('email', $additionsReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('additions-reports.email', ['additions_report' => $additionsReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $additionsReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('additions-reports.download', $additionsReport) }}" target="_blank">
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
                @can('sign', $additionsReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('additions-reports.sign', ['additions_report' => $additionsReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                        </svg>
                        Unterschreiben lassen
                    </a>
                @endcan
                @can('emailSignatureRequest', $additionsReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('additions-reports.email-signature-request', ['additions_report' => $additionsReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Unterschrift Anfrage senden
                    </a>
                @endcan
                @can('emailDownloadRequest', $additionsReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('additions-reports.email-download-request', ['additions_report' => $additionsReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use>
                        </svg>
                        Download Link senden
                    </a>
                @endcan
                @can('delete', $additionsReport)
                    <form action="{{ route('additions-reports.destroy', $additionsReport) }}" method="post" >
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
                <a href="{{ route('projects.show', $additionsReport->project) }}">{{ $additionsReport->project->name }}</a>
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
                        {{ $additionsReport->services_provided_on }}
                    </div>
                </div>
                <div class="row mt-3">
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
                        {{ $additionsReport->employee->person->name }}
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
            {!! Html::fromMarkdown($additionsReport->comment) !!}
        @endif

        @if($additionsReport->attachments()->count() > 0)
            <div class="row text-muted d-flex align-items-center mt-1">
                <div class="col">
                    <div class="d-none d-md-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                        </svg>
                        Anhänge
                    </div>
                    <a class="d-inline-flex d-md-none d-inline-flex align-items-center" data-toggle="collapse" href="#collapseAdditionsReportAttachments-{{ $additionsReport->id }}" role="button" aria-expanded="false" aria-controls="collapseAdditionsReportAttachments-{{ $additionsReport->id }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                        </svg>
                        Anhänge
                    </a>
                </div>
            </div>
            <div class="d-none d-md-block">
                <div class="row">
                    @foreach($additionsReport->attachments() as $attachment)
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
            <div class="collapse d-md-none" id="collapseAdditionsReportAttachments-{{ $additionsReport->id }}">
                <div class="row">
                    @foreach($additionsReport->attachments() as $attachment)
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
