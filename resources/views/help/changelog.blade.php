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
        ### v0.1.10-21b2904 (22.04.2022)
        * Das Ausehen der Abrechnungsseite für größere Bildschirme wurde optimierte.
        * Komfortfunktionen bei den Eingabemöglichkeiten auf der Abrechnungsseite wurden hinzugefügt.
        @endmarkdown

        @markdown
        ### v0.1.9-e251193 (20.04.2022)
        * Falls beim Hinzufügen oder Bearbeiten von Leistungsabrechnungen zwei der drei Werte Start, Ende und Menge
        angegeben werden und der dritte Werte noch nicht gesetzt ist, wird dieser automatisch berechnet. Dies
        funktioniert für Leistungen mit der Mengeneinheit Stunden (h). Die Menge wird dabei auf die maximal mögliche
        halbe Stunde gesetzt, welche in den Zeitraum passt (z.B. wenn für Start `10:30` und Ende `12:15` eingegeben ist,
        dann wird für die Menge ein Wert von `1.5` gesetzt.
        @endmarkdown

        @markdown
        ### v0.1.8-95f3390 (20.04.2022)
        * Eine Benutzereinstellung für die Anzahl der vergangenen Tage, welche standardmäßig beim Abrechnungsftiler
        angewendet werden, wurde hinzugefügt.
        * Beim Filtern der Abrechnungsdaten werden Probleme korrekt bei den Eingabefeldern angezeigt.
        * Beim Filtern der Abrechnungsdaten erscheint ein Ladebalken um den Fortschritt zu visualisieren.
        * Erfolgs- sowie Fehlermeldungen werden nun beim Filtern oder Speichern von Abrechnungsdaten angezeigt.
        @endmarkdown

        @markdown
        ### v0.1.7-e345043 (20.04.2022)
        * Ein Fehler beim Setzen von Stunden in der Tabelle bei Serviceberichten auf 0 wurde behoben.
        * Die Darstellung von Buttons zur Anzeige von Details und Problemen in der Abrechnungstabelle wurde analog
        zur Selektierung von Zeilen angepasst.
        @endmarkdown

        @markdown
        ### v0.1.6-5d10293 (19.04.2022)
        * Leistungsverzeichnisse wurden implementiert. Diese sind aufgeteilt in Materialleistungen sowie
        Lohndienstleistungen mit extra Feldern für Einheit und Kosten pro Einheit.
        * Projekten können nun erwartete Lohn- sowie Materialkosten hinzugefügt werden.
        * Die Abrechnung zum Buchen von Leistungen auf Projekte wurde implementiert.
        * Leistungsbuchungen können basierend auf Datum, Projekt, Leistung sowie Mitarbeiter gefiltert werden.
        * Neue Leistungenbuchungen können über ein Formular hinzugefügt werden.
        * Leistungsbuchungen sind in der angezeigten Tabelle bearbeitbar, mittles Knopfdruck löschbar oder
        von de Löschung wiederherstellbar.
        * Mehrere oder alle Leistungsbuchungen können selektiert werden um Lösch- oder Wiederherstellungsfunktionen
        auf alle selektierten Zeilen anzuwenden.
        * Probleme mit Eingaben werden visuell angezeigt. Durch ein Klick auf das Warnsymbol am linken Rand werden
        Details zu Problemen mit einer Zeile aufgeklappt.
        * In der Detailansicht befinden sich ebenfalls die Bemerkungen. Gibt es keine Probleme mit einer Zeile, kann
        die Zeile durch Klick auf den Pfeil links aufgeklappt werden.
        * Per Default werden beim Speichern von Änderungen werden alle Zeilen mit Problemen aufgeklappt, um Hinweise
        anzuzeigen. Dieses Verhalten kann durch eine Benutzereinstellung geändert werden.
        * Eine Einstellung zur Urlaubsleistung wurde in den allgemeinen Applikationseinstellungen implementiert. Dadurch
        wird bei der Buchung von Urlaub der verfügbare Urlaub des jeweiligen Mitarbeiters automatisch angepasst. Am Tag
        des Eintrittsdatums wird einmal jährlich die Urlaubsmenge um die in den Einstellungen gespeicherte Menge
        erhöht.
        @endmarkdown

        @markdown
        ### v0.1.5-b85c62a (29.01.2021)
        * Anhänge von Aktenvermerke und Serviceberichten werden per Email mitversendet, falls ausgewählt. Per Default
        sind alle Anhänge zum Versand vorgemerkt. Anhänge sind einzeln an- oder abwählbar.
        @endmarkdown

        @markdown
        ### v0.1.4-bcc44b5 (12.01.2021)
        * Neben Bilddateien sind nun auch Anhänge im PDF Format erlaubt.
        @endmarkdown

        @markdown
        ### v0.1.3-7fbd636 (08.01.2021)
        * Benutzern stehen nun verschiedene Einstellungen zum Aussehen der Applikation bereit. So kann die Anzahl
        von Listenelementen, die Sortierung von Kommentaren in Aufgaben und die Anzeige von erledigten Elementen
        angepasst werden.
        * Die Suchfunktion bei Aufgaben, Aktenvermerken und Serviceberichten wurde um sinnvolle Vorlagen erweitert. Diese
        sind mit einem Klick auf das Menü neben dem Such Button erreichbar.
        @endmarkdown

        @markdown
        ### v0.1.2-5b3df57 (07.01.2021)
        * Mit Authentisierung geschützte Sicherheitseinstellungen wurden für Benutzer hinzugefügt. Der Benutzer kann
        sein Passowrt nun selbst ändern.
        * Die Möglichkeit, sich mittels Zwei-Faktor-Authentisierung anzumelden, wurde implementiert. Hierzu ist eine
        entsprechende Applikation am Smartphone notwendig. Geeignete Applikationen sind etwa Aegis
        (https://play.google.com/store/apps/details?id=com.beemdevelopment.aegis) oder die Google Authenticator
        Applikation (https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2).
        @endmarkdown

        @markdown
        ### v0.1.1-855be49 (06.01.2021)
        * Beteiligte Mitarbeiter an Aufgaben und Aktenvermerken werden automatisch beim Erstellen oder Bearbeiten
        benachrichtigt.
        * Beiteiligte Mitarbeiter an Aufgaben werden automatisch beim Erstellen oder Bearbeiten eines Kommentars
        benachrichtigt.
        * Einzelne Mitarbeiter können in Bemerkungen von Aufgaben, Kommentaren von Aufgaben, Vermerken von
        Aktenvermerken und Kurzberichten von Serviceberichten erwähnt werden. Dies funktioniert durch tippen von
        `@<{{ config('app.name') }} Benutzername>` im Text. Erwähnte Benutzer werden automatisch benachrichtigt.
        * In den Applikationseinstellungen kann ein Benutzer angegeben werden, der immer benachrichtigt wird,
        sobald ein Bericht unterschrieben wurde. Der Techniker, welcher den Bericht verfasst hat, wird ebenfalls
        automatisch über eine Unterschrift benachrichtigt.
        * Diverse Verbesserungen an der Benutzeroberfläche wurden implementiert.
        @endmarkdown
    </div>

@endsection
