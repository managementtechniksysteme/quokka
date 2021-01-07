@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>{{ config('app.name') }} Versionshinweise</h3>
        </div>
    </div>

    <div class="container my-4">
        @markdown
        # Quokka v0.1.2-5b3df57 (07.01.2021)
        * Mit Authentisierung geschützte Sicherheitseinstellungen wurden für Benutzer hinzugefügt. Der Benutzer kann
        sein Passowrt nun selbst ändern.
        * Die Möglichkeit, sich mittels Zwei-Faktor-Authentisierung anzumelden, wurde implementiert. Hierzu ist eine
        entsprechende Applikation am Smartphone notwendig. Geeignete Applikationen sind etwa die Google Authenticator
        Applikation (https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2) oder andOTP
        (https://play.google.com/store/apps/details?id=org.shadowice.flocke.andotp).
        @endmarkdown

        @markdown
        # Quokka v0.1.1-855be49 (06.01.2021)
        * Beteiligte Mitarbeiter an Aufgaben und Aktenvermerken werden automatisch beim Erstellen oder Bearbeiten
        benachrichtigt.
        * Beiteiligte Mitarbeiter an Aufgaben werden automatisch beim Erstellen oder Bearbeiten eines Kommentars
        benachrichtigt.
        * Einzelne Mitarbeiter können in Bemerkungen von Aufgaben, Kommentaren von Aufgaben, Vermerken von
        Aktenvermerken und Kurzberichten von Serviceberichten erwähnt werden. Dies funktioniert durch tippen von
        `@<Quokka Benutzername>` im Text. Erwähnte Benutzer werden automatisch benachrichtigt.
        * In den Applikationseinstellungen kann ein Quokka Benutzer angegeben werden, der immer benachrichtigt wird,
        sobald ein Bericht unterschrieben wurde. Der Techniker, welcher den Bericht verfasst hat, wird ebenfalls
        automatisch über eine Unterschrift benachrichtigt.
        * Diverse Verbesserungen an der Benutzeroberfläche wurden implementiert.
        @endmarkdown
    </div>

@endsection
