@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                </svg>
                {{ config('app.name') }} Versionshinweise
            </h3>
        </div>
    </div>

    <div class="container my-4">
    @markdown
    ### v0.1.34-b5f0ed1 (21.05.2023)
    * Lieferscheine könnnen vor dem Unterschreiben zur Überprüfung heruntergeladen werden.
    @endmarkdown

    @markdown
    ### v0.1.33-626f76e (26.04.2023)
    * Lieferscheine wurden analog zu Berichten implementiert.
    * Überschriften für Kosten und Graphen im Ausdruck von Pojekten wurden um Zeiträume erweitert.
    * Beim Erstellen von Projekt PDF Dateien wird beim Filter automatisch das Startdatum aus den Stammdaten
      vorausgefüllt.
    @endmarkdown

    @markdown
    ### v0.1.32-bda69a8 (20.04.2023)
    *  Die Projektcontrolling Graphen in PDF Ausdrücken von Projektübersichten berücksichtigen nun den gefilterten
       Zeitraum.
    * Quokka Mobile steht in einer ersten Version 0.0.1 zur Verfügung. Die infoseite dazu befindet sich hier:
      [Quokka Mobile]({{route('quokka-mobile.index')}}). Eine Aufgabe zur Diskusion findet sich [hier]({{route('tasks.show', 464)}}).
    @endmarkdown

    @markdown
      ### v0.1.31-62bb242 (06.04.2023)
      * Finanzielle Auswertungen wurde in manuelle und Projektauwertung aufgeteilt.
      * Projektauswertungen mit Filtermöglichkeit sind im Menüpunkt Projekte -> Controlling zu finden.
    @endmarkdown

    @markdown
      ### v0.1.30-fa208e8 (03.04.2023)
      * Falls die entsprechende Option zum Ausblenden von Listenelementen in den Benutzereinstellungen gesetzt ist,
        werden beendete Projekte ab dem Tag nach dem Ende automatisch aus der Übersicht ausgeblendet.
    @endmarkdown

    @markdown
      ### v0.1.30-dcc5911 (02.04.2023)
      * Die Finanzübersicht wurde implementiert. Es besteht die Möglichkeit, zusammenfassende sowie gruppenspezifische
        Graphen anzuzeigen. Weiters kann eine Übersicht in PDF Format generiert werden. Finanzen können manuell
        mittels Gruppen und zugehörigen Einträgen verwaltet werden. Eine Gruppe kann hierbei einem Projekt zugeordnet
        sein oder als eigenständige Sammlung behandelt werden.
      * In diesem Zuge wurden Projekte um zwei Einstellungsmöglichkeiten erweitert. Diese geben an, ob sich ein
        Projekt in der Vorphase befindet und ob dem Projekt zugeordnete Leistungen in der Finanzübersicht
        berücksichtigt werden sollen.
      * Finanzwerte für einen Projekt werden jetzt ebenfalls graphisch in der Projektdetail Seite dargestellt.
      * Ein Hinweis zur Verwendung von A4 Hochformat Anhängen wurde zum Formular für Prüfberichte von
        Durchflussmesseinrichtungen hinzugefügt.
      * Ein Fehler wurde beim Erstellen von Aufgaben PDFs behoben, bei dem illegale Zeichen im resultierenden
        Dateienamen eine PDF Generierung verhinderten.
      * Kleinere Ausbesserungen von Schreibfehler und Verbesserung der Benutzeroberfläche.
      @endmarkdown

      @markdown
      ### v0.1.29-f73070b (26.03.2023)
      * Das zugrundelegende Framework wurde auf die neueste Version aktualisiert, um zukunftssicher weitere
        Funktionen entwickeln zu können.
      @endmarkdown

      @markdown
      ### v0.1.28-96e607c (21.02.2023)
      * Aktenvermerke können beim Erstellen nun als Entwurf markiert werden. Solange ein Aktenvermerk im Entwurfsstatus
        bearbeitet wird, werden keine Benachrichtigungen beim Speichern an beteiligte Personen mit Quokka Zugang
        gesendet. Erst beim Veröffentlichen (entweder beim finalen Bearbeiten des Entwurfs oder durch eine separate
        Aktion) werden Benachrichtigungen abgearbeitet. Benachrichtigungen nach Bearbeiten von veröffentlichten
        Aktenvermerken werden wie bisher behandelt. Das Versenden von Emails bzw. Erstellen von PDF Dateien ist erst
        nach Veröffentlichung möglich.
      * Aus einer Notiz im Notizbuch kann jetzt neben einer Aufgabe auch ein Aktenvermerk erstellt werden.
      * Kleinere Fehlerbehebungen und Verbesserungen wurden implementiert.
      @endmarkdown

      @markdown
      ### v0.1.27-e162fbb (08.02.2023)
      * Ein Fehler beim Filtern von Listen wurde behoben. Durch das Entfernen eines Filters bei vorhandenem
        Defaultfilter wurde der Defaultfilter bei Seitenwechsel fehlerhafterweise wieder aktiviert. Somit ergab sich
        unter Umständen auh eine ungültige Seitenzahl, wonach bei einem Klick die Meldung über keine vorhandenen
        Elemente im System angezeigt wurde.
      * Ein Fehler beim Erstellen von Serviceberichten aus der Abrechnung wurde behoben. Die Funktion zur Prüfung
        überlappender, bereits existierender Serviceberichte produzierte eine ungewollte Ausnahme.
      @endmarkdown

      @markdown
      ### v0.1.26-de5fbe7 (03.02.2023)
      * Beim Erstellen und Bearbeiten von Serviceberichten wird automatisch geprüft, ob zu den eingegebenen
      Daten bereits andere Serviceberichte des Mitarbeiters existieren. Diese werden in Echtzeit als Links zur
      Prüfung über der Leistungstabelle angezeigt.
      * Ein Fehler bei der Suche in Fehlerberichten wurde behoben.
      @endmarkdown

      @markdown
      ### v0.1.25-e7e55e8 (27.01.2023)
      * Ein privates Notizbuch steht nun zur Verfügung. Dieses kann über das Ausklappmenü durch Klick auf den
      Benutzernamen erreicht werden. Notizen werden wie jegliche andere Elemente behandelt. Sie können per Email
      versendet oder als PDF gedruckt werden. Weiters besteht die Möglichkeit, eine Notiz schnell in eine Aufgabe
      umzuwandeln. **Jeder Benutzer sieht nur seine eigenen, persönlchen Notizen.**
      * Fahrzeuge können ab jetzt als Privatfahrzeuge markiert werden. Somit werden alle auf solche Farhzeuge gebuchten
      Fahrten als private Fahrten behandelt. Diese werden bei der Abrechnungsauswertung berücksichtigt und automatisch
      für den angegebenen Zeitraum aufsummiert angezeigt. Zusätzlich wird der entsprechende Euro Betrag ausgegeben.
      * Bei entsprechenden Berechtiugngen können Abrechnungsauswrtungen jetzt über mehrere Mitarbeiter gleichzeitig
      erfolgen. In diesem fall wird in der Tabelle zusätzlich das Mitarbeiterkürzel angezeigt und Kilometer, die mit
      Privatfahrzeugen gefahren wurden, werden für alle gewählten Mitarbeiter angezeigt.
      * Bereits unterschriebene Berichte dürfen nun gelöscht werden, falls die Berechtigung zum Erledigen der Berichte
      vorhanden ist.
      * Bei Projektauswertungen werden Projektbemerkungen nun vor der Tabelle angezeigt.
      @endmarkdown

      @markdown
      ### v0.1.24-cc21b26 (11.11.2022)
      * In den Applikationseinstellungen können nun Kosten für Fahrtkilometer eingetragen werden. Diese werden in der
      Projektübersicht sowie bei PDF Auswertungen angezeigt. Weiters werden die Fahrtkosten in den aktuellen Gesamtkosten
      berücksichtigt.
      @endmarkdown

      @markdown
      ### v0.1.23-3e59f5a (10.10.2022)
      * Implementierung von Prüfberichten von Durchflussmesseinrichtungen
      * Ein Fehler wurde behoben, bei dem alle Fahrtenbucheinträge eines Tages zu einem Servicebericht hinzugefügt
      wurden, falls dieser aus der Abrechnung oder dem Fahrtenbuch erstellt wurde. Nun werden lediglich die eigenen
      Fahrten als Kilometer eingetragen.
      @endmarkdown

      @markdown
      ### v0.1.22-596a827 (25.08.2022)
      * Listen von Serviceberichten können nun als PDF Format erstellt werden.
      * Ein Fehler bei der automatischen Erhöhung des Urlaubs um die jährlich
      gutzuschreibende Menge am Eintrittstag wurde behoben.
      * Die Anpassung des Urlaubs bei Buchung in der Abrechnung wurde verbessert.
      Somit wird der verfügbare Urlaub hoffentlich ab jetzt korrekt entsprechend der
      Leistungsabrechnung angepasst.
      * Beim Verschieben von nummerierten Elementen zwischen Projekten (wie etwa
      Serviceberichte) wird nun die erste freie Nummer des neu zugewiesenen Projektes
      für das entsprechende Element vergeben. Bis jetzt blieb die Nummer unverändert.
      Diese konnte zu doppeleten Nummern oder größeren Lücken in der Nummerierung führen.
      Wird ein Element wieder zum ursprünglichen Projekt zurückverschoben, so wird ebenfalls
      die nächste freie Nummer vergeben. Die ursprüngliche Nummer bleibt unbenutzt.
      @endmarkdown

      @markdown
      ### v0.1.21-00d1735 (29.07.2022)
      * Beheben eines Fehlers der bei Serviceberichten Stunden beim falschen
      Datum aufsummiert, falls der selbe Tag in einem anderen Monat bereits
      vorhanden ist.
      @endmarkdown

      @markdown
      ### v0.1.20-6d2c664 (12.07.2022)
      * Implementierung von Teilrechnungen zu Projekten für berechtigte
      Benutzer.
      * Bei Berichten wird der Benutzer bei Erledigung mitgespeichert
      und in der Oberfläche sowie in Ausdrucken angezeigt.
      * Aufgabenaktivitäten werden nun in der Liste analog zu
      Kommentaren angezeigt. Bei Erledigung einer Aufgabe wird die
      Aktion zusäztlich farblich hervorgehoben. Die Aktionen beziehen
      sich auf Änderungen an der Aufgabe (z.B. beteiligte Mitarbeiter,
      Titel oder Start- bzw. Fälligkeitsdatum).
      * Gesendete Emails können bei entsprechenden Berechtigungen nun
      in einer Übersicht angesehen werden (dient eher zu Kontrollen).
      * Verbesserungen an diversen Anzeigen.
      * Korrektur von Anzeige- und Rechtschreibfehlern.
      * Fehlerbehebungen bei Berechtigungen.
      * Dateinamen von Aufgabenlisten als PDF werden nun korrekt
      vergeben.
      @endmarkdown

      @markdown
      ### v0.1.19-3532f96 (24.06.2022)
      * Auswertungen von Projekten stehen nun zur Verfügung.
      * Auswertungen von Projektlisten stehen nun zur Verfügung.
      * Auswertungen von Aufgabenlisten stehen nun zur Verfügung.
      * Ein Servicebericht kann nun direkt aus der Abrechnung oder dem Fahrtenbuch erstellt werden. Hierbei können die
      gewünschten Elemente selektiert werden und es erscheint ein Button zum Erstellen des Berichtes. Dies funktioniert
      nur, wenn alle Elemente einem Projekt angehören, alle Elemente dem aktuellen Benutzer zugeordnet sind und im Fall
      von Leistungsabrechnungen alle Elemente studenbasiert sind. Die Anzahl an Stunden bzw. Kilometern wird automatisch
      pro Tag aufsummiert und zusammen mit dem Projekt in den jeweiligen Eingabefeldern hinterlegt. Kommentare von
      abgerechneten Leistungen werden nach Datum sortiert in das Kurzbericht Feld des Serviceberichtes eingetragen.
      Falls ein Servicebericht aus der Abrechnung erstellt wird, so werden automatisch die gefahrenen Kilometer der selben
      Tage des angemeldeten Benutzers gesucht und einetragen. Falls ein Servicebericht aus dem Fahrtenbuch erstell wird,
      werden auotmatische alle Leistungen des angemeldeten Benutzers im gleichen Zeitbereich gesucht und eingetragen.
      * Benachrichtigungsziele können nun verwaltet werden. Hierbei kann festgelgt werden, welche spezifischen
      Benachrichtigungen per Email bzw. Push übermittelt werden sollen.
      * Es kann festgelegt werden, ob man über eigenen Aktionen benachrichtig werden möchte.
      * Benachrichtigungen in der Applikation wurden implementiert. Alle Benachrichtigungen (außer bei eigenen
      Aktionen, falls nicht erwünscht), sind in Quokka selbst sichtbar. Diese können als gelesen markiert werden.
      * Eine tägliche Zusammenfassung von ungelesenen Benachrichtigungen wird per Email versendet.
      * Gelesene Benachrichtigungen, welche älter als einen Monat sind, können automatisch aus dem System entfernt
      werden, falls die entsprechende Einstellung in den globalen Applikationseinstellungen gesetzt ist.
      * Projekten können aktuell verrechnete Kosten zugewiesen werden, welche dann den gebuchten Leistungen
      gegenüber gestellt wird.
      * Die Erkennung von Änderungen an Elementen wurde verbessert, so dass ungeänderte Elemente, welche gespeichert
      werden, keine Benachrichtigungen auslösen.
      * Eine Anzeige und die Möglichkeit zum Löschen von Serverfehler (bei entsprechenden Berechtigungen) für besseren
      Support wurde implementiert.
      * Eine Einstellungen zur standardmäßigen Anzeige von lediglich neuen Berichten wurde hinzugefügt.
      * Kommentare in Aufgaben werden in der Liste zuletzt geänderter Elemente nun separat aufgeführt.
      * Tabellen in Texten werden nun innerhalb der Applikation besser dargestellt.
      * Die Verzögerung bei Verwendung der Kommando Palette wurde erhöht, um das Laden von Ergebnissen zu verbessern.
      * Ein Fehler bei der Auswertung vom Fahrtenbuch wurde behoben (alle Fahrten wurden mit dme aktuellen Datum
      angezeigt).
      * Ein Fehler wurde behoben, bei dem nicht intialisierte Elemente vor der Anmeldung zu Serverfehlern führten.
      * Fehler bei Anzeigeelementen wurden behoben.
      * Ein Fehler bei der Anzeige von Mengen an Elementen in Überschriften wurde behoben.
      * Rechtschreibfehler wurden behoben.
      @endmarkdown

      @markdown
      ### v0.1.18-478280a (09.06.2022)
      * Bautagesberichte können nun analog zu Serviceberichten verwaltet werden.
      * Eine Kommandopalette wurde implementiert. Diese kann für schnelle Navigation und Aktionen verwendet werden.
      Falls eine Aktion eine weitere Eingabe erfordert, werden Listen nach Suchergebnissen gefiltert (z.B. bei der
      Anzeige einer spezifischen Aufgabe). Die Ergebnisse werden nach dem Start der Eingabe angezeigt. Die Palette
      kann mit `Strg + k`, `Strg + /` oder `Strg + Leertaste` in Windows oder Linux und `Cmd + k`, `Cmd + /` oder
      `Cmd + Leertaste` in MacOS aufgerufen werden. Weiters kann die Palette mit einem Klick auf das Shortcut
      Symbol der Suche in der Navigationsleiste aktiviert werden.
      * Eine globale Suche wurde implementiert. Diese kann mittels des Suchfeldes in der Navigationsleiste oder über
      die Kommandpalette (`Global suchen` verwendet werden.
      * Eine Anzeige von zuletzt geänderten Elementen ist nun bei entsprechenden Berechtiungen verfügbar.
      * Fehlerberichte von unerwarteten Serverfehlern können bei entsprechender Berechtigung nun in der Oberfläche
      angezeigt und gelöscht werden.
      * Beim Editieren von Texten können Leerzeichen am Ende von Zeilen visuell angezeigt werden. Die Anzeige kann
      mittels der Paragraph Schaltfläche ganz rechts aktiviert bzw. deaktiviert werden.
      * Die Suche von Elementen wurde verbessert. So werden Aufgaben nun etwa auch gefunden, wenn ein Teil der
      zugeordneten Firma oder ein Mitarbeitername des verantwortlichen oder beteiligter Mitarbeiter gefunden wird.
      * Die Vorschau von Texten wurde gemäß des Standards angepasst, so dass ein einzelner Zeilenumbruch in der
      Eingabe nicht als neue Zeile in der Vorschau angezeigt wird. Dies ist nun konsistent mit Ausdrucken.
      * Diverse Anzeigeelemente wurde verbessert.
      * Ein Fehler bei der Suche von Elementen wurde behoben.
      * Ein Fehler bei der Erstellung von Mitarbeitern wurde behoben.
      * Ein Fehler bei der Darstellungen von leeren Listen wurde behoben.
      * Ein Fehler bei der Berechnung von Werten in der Übersicht wurde behoben.
      @endmarkdown

      @markdown
      ### v0.1.17-39ad206 (25.05.2022)
      * Prüfberichte können nun analog zu Serviceberichten verwaltet werden.
      * Ein Fehler, der alle Aufgaben, Aktenvermerke, Serviceberichte oder Regieberichte bei Projekten anzeigte, wurde
      behoben.
      * Berechtigungskontrollen wurden gegen unabsichtliches eintippen von nicht anzeigbaren Elementen in der URL
      Leiste erweitert (betrifft Kopieren von Elementen).
      * Rechtschreibfehler wurden behoben.
      @endmarkdown

      @markdown
      ### v0.1.16-8439059 (20.05.2022)
      * Die persönliche Übersicht ist nun implementiert. Diese zeigt diverse nützliche Indikatoren.
      * Regieberichte können nun analog zu Serviceberichten verwaltet werden.
      * Auswertungen von Fahrten können erstellt werden. Die erstellte PDF Datei wird automatisch basierend auf dem
      aktuellen Anzeigefilter erstellt.
      * Für Firmen kann eine Ansprechperson angegeben werden. Die Email Adresse dieser Person wird dann automatisch
      beim Versenden von Berichten in das Feld eingetragen. Falls keine Ansprechperson vorhanden ist, wird weiterhin
      die Email Adresse der Firma eingetragen, falls eine angegeben ist.
      * Bei unerwarteten Serverfehlern wird eine Identifikation angezeigt, welche für bessere Hilfe kopiert und
      mitgeteilt werden kann.
      * Bei entsprechender Berechtigung kann sich ein Benutzer als ein anderer Benutzer anmelden, um etwa bessere
      Hilfe leisten zu können.
      * Eine Einstellung zur standardmäßigen Filterung von eigenen Berichten ist nun in den Benutzereinstellungen
      verfügbar.
      * Eine Einstellung zur Anzeige oder zum Verstecken von Kostenindikatoren in Projektlisten ist nun verwendbar.
      * Es werden standardmäßig nur eigene Fahrten im Fahrtenbuch angezeigt.
      * Ein Fehler bei der automatischen Gutschrift von Urlaubstagen wurde behoben.
      * Ein Fehler bei der Summierung von Abrechnungskosten auf Projekten zur Anzeige von aktuellen Indikatoren wurde
      behoben.
      * Ein Fehler, der verhinderte, dass PDF Dateien von erledigten Serviceberichten erstellt werden konnten, wurde
      behoben.
      * Ein Fehler, der die falsche Anzahl an Listenelementen in der Überschrift anzeigte, wurde behoben.
      * Rechtschreibfehler wurden behoben.
      @endmarkdown

      @markdown
      ### v0.1.15-99f2fda (04.05.2022)
      * Auswertungen von Abrechnungen können nun erstellt werden. Die Auswertung wird automatisch basierend auf den im
      Anzeigefilter angegebenen Kriterien erstellt. Für Monatsaauswertungen empfiehlt es sich daher, lediglich den
      Zeitbereich im Anzeigefilter einzustellen.
      * Mit entsprechenden Berechtigungen kann auch die Person gewählt werden, für welche die Auswertung durchgeführt
      werden soll.
      @endmarkdown

      @markdown
      ### v0.1.14-7228f12 (02.05.2022)
      * Materialleistungen können nun mit bis zu zwei Nachkommastellen angegeben werden. Lohndienstleistungen müssen
      weiterhin ein Vielfaches des in den Einstellungen festgelegten Wertes sein.
      * Aktenvermerke, Aufgaben und Rollen können nun kopiert werden um einfach eine neue Instanz mit vorgegebenen
      Werten anzulegen,
      * Dem Kunden werden beim Unterschreiben und Herunterladen von Serviceberichten keine internen Links mehr
      angezeigt.
      * Ein Fehler bei der Eingabe von Abrechnungen und Fahrten wurde behoben bei dem neu angelegte Zeilen nicht
      bearbeitet oder gelöscht werden konnten bevor sie einmal gepspeichert wurden.
      * Korrekturen an Texten mit Schreibfehlern wurden in Ansichten vorgenommen.
      @endmarkdown

      @markdown
      ### v0.1.13-ce3aa07 (01.05.2022)
      * Das Berechtigungssystem wurde implementiert.
      * Rollen als Vorlagen (Sammlung von Berechtigungen) können verwaltet werden.
      * Benutzern können alle Berechtigungen einer Rolle oder individuelle Berechtigungen zugewiesen werden.
      Hinweis: Geänderte Berechtigungen von Rollen (Vorlagen) wirken sich lediglich auf die Vorlage und nicht auf
      bereits vergebene Benutzerberechtigungen aus.
      @endmarkdown

      @markdown
      ### v0.1.12-e1a3664 (28.04.2022)
      * Der Fuhrpark zum Verwalten von Fahrzeugen wurde implementiert.
      * Das Fahrtenbuch zum Eintragen von Fahrten mit Fahrzeugen wurde implementiert. Der Start Kilometerstand wird
      beim Auswählen eines Fahrzeuges für den Eintrag einer neuen Fahrt automatisch aus den bisher gepspeicherten
      Fahrten ermittelt. Bei Fehlen eines der drei Werte Start Kilometerstand, Ende Kilometerstand und gefahrene
      Kilometer wird dieser automatisch berechnet und in das jeweilige Feld eingetragen. Nach dem Hinzufügen einer
      Fahrt wird der eingegebene Ziel Ort automatisch in das Start Feld übernommen. Die Werte können jederzeit
      händisch in der Eingabe überschrieben werden.
      * Der Kilometerstand zur Anzeige im Fuhrpark wird aus den eingetragenen Fahrten ermittelt (höchster Ende
      Kilometerstand aller Fahrten für das Fahrzeug).
      * In der Abrechnung sowie im Fahrtenbuch werden die aktuell in der Tabelle vorhandenen Einträge sowie die Anzahl
      der neuen, geänderten und zu löschenden Datensätze neben der Überschrift angezeigt.
      * In der Abrechnung können nun mehrzeilige Kommentare eingegeben werden.
      * Aufgaben müssen innerhalb eines Projektes nun nicht mehr einen eindeutigen Namen besitzen. Dadurch können
      Materialbestellungen im entsprechenden Projekt gleich betitelt werden.
      * Die Konvertierung von Markdown zur Anzeige in der Applikation sowie zur Erstellung von PDF Dateien wird nun
      einheitlich von der selben Funktion durchgeführt. Somit sollte die Wahrschreinlichkeit zu Abweichungen
      verringert sein. In der Echtzeit Vorschau des Editors kann es nach wie vor zu kleinen Unterschieden kommen weil
      dieser eine eigene Vorschaufunktion verwendet.
      * Bei der Benachrichtgung für Anpassungen von Urlaub wurde ein Problem behoben (die Push Benachrichtigung
      funktionierte nicht korrekt).
      @endmarkdown

      @markdown
      ### v0.1.11-8b177cf (25.04.2022)
      * Bei Abrechnungen können nun mehrzeilige Kommentare eingegeben werden.
      * Die Anzahl von neuen, geänderten und gelöschten Kommentaren wird bei der Abrechnungstabelle nun angezeigt.
      * Das Datum für neue Zeilen in der Abrechnung bleibt nun von vorherigen Eingaben für eine schnellere Eingabe erhalten.
      * Diverse Anzeigeelemente wurden vereinheitlicht, sowie Darstellungsfehler behoben.
      @endmarkdown

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
