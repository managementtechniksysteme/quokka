@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#smartphone"></use>
                </svg>
                Quokka Mobile
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <h3>Quokka Mobile - Quokka zum Mitnehmen</h3>

        <p class="text-muted lead">
            Die erste Version von Quokka Mobile steht zur Verwendung bereit. Mit einem Fokus auf intuitiver Bedienung mit
            nativer Bedienoberfläche können nun auch von unterwegs ganz bequem Einträge erledigt werden. Zum Start stehen
            die Abrechnungsfunktion sowie das Fahrtenbuch zur Verfügung.
        </p>

        <h3>Diskussion und Funktionswünsche</h3>

        <p class="text-muted lead">
            Der Funktionsumfang ist im Gegensatz zum großen Bruder, der Webversion, zum Start auf das Wesentlichste
            beschränkt. Diese wurden jedoch möglichst getreu der bekannten Implementierung verwirklicht. Bei aktiver
            Verwendung der mobilen Version können ohne Weiteres zusätzliche Funktionen eingebaut werden.
        </p>
        <p class="text-muted lead">
            In folgender Aufgabe können Funktionswünsche eingetragen werden.
        </p>

        <div class="text-center">
            <a href="{{ route('tasks.show', 464) }}"><btn class="btn btn-lg btn-primary" type="btn-link"> Zur Aufgabe für Quokka Mobile</btn></a>
        </div>

        <h3 class="mt-5">Quokka Mobile für Android herunterladen</h3>

        <p class="text-muted lead">
            Quokka Mobile kann unter folgendem Link heruntergeladen werden. Da die Version nur intern vertrieben wird
            und es sich um eine Erstversion handelt, ist eine manuelle Installation der Datei notwendig. Am einfachsten
            kann Quokka Mobile installiert werden, indem die Datei direkt auf dem entsprechenden Gerät heruntergeladen
            wird.
        </p>

        <div class="alert alert-warning mt-1" role="alert">
            <div class="d-inline-flex align-items-center">
                <svg class="icon icon-24 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                </svg>
                <p class="m-0">
                    Um manuell heruntergeladene Applikationen installieren zu können, welche nicht vom Google Play Store
                    stammen, muss die entsprechende Berechtigung auf dem Gerät aktiviert werden. Diese findet sich
                    üblicherweise in den Einstellungen (Systemsicherheit) unter <strong>Installationsquellen</strong>.
                </p>
            </div>
        </div>

        <div class="text-center">
            <a href="https://github.com/managementtechniksysteme/quokka/raw/master/public/mobile/quokka-mobile-0.0.1.apk"><btn class="btn btn-lg btn-primary" type="btn-link">Quokka Mobile herunterladen</btn></a>
        </div>

        <h3 class="mt-5">Funktionsüberblick</h3>

        <div class="row">
            <div class="card-deck">
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <img class="card-img-top" src="{{asset('mobile/images/login.png')}}">
                        <div class="card-body">
                            <h5 class="card-title">Nun auch im kleinen Format</h5>
                            <p class="card-text text-muted">Quokka Mobile bietet die wichtigsten Funktionen von Quokka in
                            einer nativen Applikation für Smartphones.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <img class="card-img-top" src="{{asset('mobile/images/dashboard.png')}}">
                        <div class="card-body">
                            <h5 class="card-title">Zur schnellen Verwaltung von Unterwegs</h5>
                            <p class="card-text text-muted">Mit Quokka Mobile lassen sich nun auch unterwegs spielend
                            Einträge erledigen oder der überblick erfassen.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <img class="card-img-top" src="{{asset('mobile/images/convenience.gif')}}">
                        <div class="card-body">
                            <h5 class="card-title">Inklusive bewährten Funktionen</h5>
                            <p class="card-text text-muted">Auch die Komfortfunktionen wurden übernommen. So werden etwa
                            die Stunden automatisch basierend auf eingegebenen Zeiten berechnet.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <img class="card-img-top" src="{{asset('mobile/images/filters.png')}}">
                        <div class="card-body">
                            <h5 class="card-title">Optimiert auf mobile Bedienung</h5>
                            <p class="card-text text-muted">Durch die nativen Bedienelemente und durchdachte Gestaltung
                            wird die Verwendung vereinfacht.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <img class="card-img-top" src="{{asset('mobile/images/accounting.png')}}">
                        <div class="card-body">
                            <h5 class="card-title">Leistungen unkompliziert eintragen</h5>
                            <p class="card-text text-muted">Leistungen können wie gewohnt verwaltet werden. Es stehen
                            gleichen Filter und Eingabemöglichkeiten wie in der Webversion zur Verfügung.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <img class="card-img-top" src="{{asset('mobile/images/logbook.png')}}">
                        <div class="card-body">
                            <h5 class="card-title">Fahrten unmittelbar aufzeichnen</h5>
                            <p class="card-text text-muted"> Auch das Fahrtenbuch kann analog zur Webversion in Quokka
                            Mobile bedient werden. Somit kann gleich bei der Ankunft eine Fahr eingetragen und aus den
                            Gedanken verworfen werden.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection
