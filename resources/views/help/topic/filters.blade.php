@extends('layouts.app')

{{-- Mobile: help topics are reached from help/index's own list (q-row items),
     same list→detail relationship as any other module — promote the topic's
     own title into the app bar (back chevron to help.index) instead of the
     generic "Hilfe" section label, no kebab since there are no actions
     (2026-07-22, user request). --}}
@section('mobile-detail-bar')
    <a href="{{ route('help.index') }}" class="q-appbar__btn" aria-label="Zurück zu Hilfe">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title">Filter</span>
@endsection

@section('content')
    <div class="q-container q-container--narrow">
        @include('help.breadcrumb')

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#funnel"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Hilfe</div>
                    <h1 class="q-title">Filter</h1>
                </div>
            </div>
        </div>

        <div class="mt-2 mt-md-4">
        <a id="top"></a>

        {{-- q-card__body's own $q-pad token, not an ad-hoc p-3 utility — the
             mismatched padding read as extra bottom space on mobile
             (2026-07-22, user). --}}
        <div class="q-card mb-4">
            <div class="q-card__body d-flex flex-wrap gap-2">
            <a class="btn btn-sm q-btn" href="#allgemeines">Allgemeines</a>
        @can('viewAny', \App\Models\Address::class)
            <a class="btn btn-sm q-btn" href="#adressen">Adressen</a>
        @endcan
        @can('viewAny', \App\Models\Memo::class)
            <a class="btn btn-sm q-btn" href="#aktenvermerke">Aktenvermerke</a>
        @endcan
        @can('viewAny', \App\Models\Task::class)
            <a class="btn btn-sm q-btn" href="#aufgaben">Aufgaben</a>
        @endcan
        @can('viewAny', \App\Models\ConstructionReport::class)
            <a class="btn btn-sm q-btn" href="#bautagesberichte">Bautagesberichte</a>
        @endcan
        @can('viewAny', \App\Models\Vehicle::class)
            <a class="btn btn-sm q-btn" href="#fahrzeuge">Fahrzeuge</a>
        @endcan
        @can('viewAny', \App\Models\FinanceGroups::class)
            <a class="btn btn-sm q-btn" href="#finanzen">Finanzen</a>
        @endcan
        @can('viewAny', \App\Models\Company::class)
            <a class="btn btn-sm q-btn" href="#firmen">Firmen</a>
        @endcan
        @if(auth()->user()->can('viewAny', \App\Models\MaterialService::class) || auth()->user()->can('viewAny', \App\Models\WageService::class))
            <a class="btn btn-sm q-btn" href="#leistungen">Leistungen</a>
        @endif
        @can('viewAny', \App\Models\DeliveryNote::class)
            <a class="btn btn-sm q-btn" href="#lieferscheine">Lieferscheine</a>
        @endcan
        @can('viewAny', \App\Models\Employee::class)
            <a class="btn btn-sm q-btn" href="#mitarbeiter">Mitarbeiter</a>
        @endcan
        @can('viewAny', \App\Models\Note::class)
            <a class="btn btn-sm q-btn" href="#notizen">Notizen</a>
        @endcan
        @can('viewAny', \App\Models\Person::class)
            <a class="btn btn-sm q-btn" href="#personen">Personen</a>
        @endcan
        @can('viewAny', \App\Models\Project::class)
            <a class="btn btn-sm q-btn" href="#projekte">Projekte</a>
        @endcan
        @can('viewAny', \App\Models\InspectionReport::class)
            <a class="btn btn-sm q-btn" href="#pruefberichte">Prüfberichte</a>
        @endcan
        @can('viewAny', \App\Models\FlowMeterInspectionReport::class)
            <a class="btn btn-sm q-btn" href="#pruefberichte_fuer_durchflussmesseinrichtungen">Prüfberichte Durchfluss</a>
        @endcan
        @can('viewAny', \App\Models\AdditionsReport::class)
            <a class="btn btn-sm q-btn" href="#regieberichte">Regieberichte</a>
        @endcan
        @can('viewAny', \Spatie\Permission\Models\Role::class)
            <a class="btn btn-sm q-btn" href="#rollen">Rollen</a>
        @endcan
        @can('viewAny', \App\Models\ServiceReport::class)
            <a class="btn btn-sm q-btn" href="#serviceberichte">Serviceberichte</a>
        @endcan
            </div>
        </div>

        <a id="allgemeines"></a>
        <h4 class="mt-4">Allgemeines</h4>

        @markdown
        Die in {{ config('app.name') }} implementierte Suche zum Filtern von Listen kann mit speziellen Suchbegriffen verwendet werden.
        Diese filtern Attribute nach angegebenen Werten. Somit ist eine genauere Suche möglich. Die Verwendung dieser
        Begriffe ist kontextabhängig.

        Die verwendbaren Begriffe werden im Folgenden beschrieben.
        @endmarkdown

        <div class="q-banner q-banner--info mb-3">
            <svg class="icon-bs icon-16 flex-shrink-0"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
            <div>Alle Suchbegriffe, die einem speziellen Begriff entsprechen, werden durch eine logische <em>UND</em> Verknüpfung ausgewertet. Durch Anführen eines speziellen Suchbegriffs mit einem Rufzeichen (!) wird der Suchbegriff negiert. Die Suche in Standardattributen wird ebenfalls durch eine logische <em>UND</em> Verknüpfung angehängt.</div>
        </div>

        <div class="q-banner q-banner--info mb-3">
            <svg class="icon-bs icon-16 flex-shrink-0"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
            <div>Alle Suchbegriffe, die nicht einem speziellen Begriff entsprechen, werden in den kontextabhängigen Standardattributen gesucht. Hierbei wird eine logische <em>ODER</em> Verknüpfung verwendet. Groß- und Kleinschreibung wird bei der Suche ignoriert.</div>
        </div>

        @can('viewAny', \App\Models\Address::class)
        <a id="adressen"></a>
        <h4 class="mt-4">Adressen</h4>

        @markdown
        **Standardattribute**
        * Name
        * Straße und Nummer
        * Postleitzahl
        * Ort
        * Firmenname

        **Spezielle Suchbegriffe**
        * keine

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            MTS
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Adressen, in denen sich der Begriff `MTS` in Name, Straße und Nummer, Postleizahl oder Ort befindet.
            Hierbei können andere Begriffe vor oder nach dem Suchbegriff vorhanden sein.

            Mögliche Ergebnisse
            * MTS
            * MTS Management Technik Systeme GmbH & CO KG
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\Memo::class)
        <a id="aktenvermerke"></a>
        <h4 class="mt-4">Aktenvermerke</h4>

        @markdown
        **Standardattribute**
        * Titel
        * Kundenname
        * Projektname
        * Name und {{ config('app.name') }} Benutzername des Verfassers
        * Name und {{ config('app.name') }} Benutzerbane des Empfängers
        * Name und {{ config('app.name') }} Benutzername von anwesenden Personen
        * Name und {{ config('app.name') }} Benutzername von Personen im Verteiler

        **Spezielle Suchbegriffe**
        * `hat:folgetermin`
        Der Aktenvermerk at einen Folgetermin in der Zukunft.

        * `nummer:<Nummer>` oder `n:<Nummer>`
        Der Aktenvermerk hat die Nummer `<Nummer>`.

        * `ist:entwurf` oder `ist:e`
        Der Aktenvermerk befindet sich im Entwurfsstatus.

        * `projekt:<Projekt Name>` oder `p:<Projekt Name>`
        Der Aktenvermerk ist dem Projekt mit dem Namen `<Projekt Name>` zugeordnet.

        * `firma:<Firma Name>` oder `f:<Firma Name>`
        Der Aktenvermerk ist der Firma mit dem Namen `<Firma Name>` zugeordnet.

        * `von:<{{ config('app.name') }} Benutzername>`
        Der Aktenvermerk wurde vom Mitarbeiter mit dem {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>` verfasst.

        * `an:<Name>`
        Der Aktenvermerk wurde an die Person mit dem Namen `<Name>` verfasst.

        * `beteiligt:<Name>` oder `b:<Name>`
        Die Person mit dem Namen `<Name>` ist am Aktenvermerk beteiligt.

        * `beteiligt_mitarbeiter:<{{ config('app.name') }} Benutzername>` oder `bm:<{{ config('app.name') }} Benutzername>`
        Der Mitarbeiter mit dem {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>` ist am Aktenvermerk beteiligt.

        * `verständigt:<Name>` oder `v:<Name>`
        Die Person mit dem Namen `<Name>` wird über den Aktenvermerk verständigt.

        * `verständigt_mitarbeiter:<{{ config('app.name') }} Benutzername>` oder `vm:<{{ config('app.name') }} Benutzername>`
        Der Mitarbeiter mit dem {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>` wird über den Aktenvermerk verständigt.


        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            Besprechung zur Inbetriebnahme
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Aktenvermerke, in denen sich die Begriffe `Besprechung`, `zur` und `Inbetriebnahme` in dierser
            Reihenfolge im Titel befinden.
            Hierbei können andere Begriffe vor, zwischen oder nach den Suchbegriffen vorhanden sein.

            Mögliche Ergebnisse
            * Besprechung zur Inbetriebnahme
            * MTS Besprechung zur Inbetriebnahme der neuen Kaffeemaschine.
            @endmarkdown
        </div>

        <div class="q-example-input">
            @markdown
            ```
            Besprechung zur Inbetriebnahme von:mst "an:Angelika Steiner"
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Aktenvermerke, in denen sich die Begriffe `Besprechung`, `zur` und `Inbetriebnahme` in dieser
            Reiehnfolge im Titel befinden, der Verfasser den {{ config('app.name') }} Benutzernamen `mst` hat und der Empfänger den Namen
            `Angelika Steiner` hat.
            @endmarkdown
        </div>

        <div class="q-example-input">
            @markdown
            ```
            "p:MTS000000 [Intern]" !hat:folgetermin
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Aktenvermerke, die dem Projekt mit dem Namen `MTS000000 [Intern]` zugeordnet sind und keine
            Folgetermine in der Zukunft aufweisen.
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\Task::class)
        <a id="aufgaben"></a>
        <h4 class="mt-4">Aufgaben</h4>

        @markdown
        **Standardattribute**
        * Name
        * Projektname
        * Firmenname
        * Name oder {{ config('app.name') }} Benutzername des verantwortlichen Mitarbeiters
        * Name oder {{ config('app.name') }} Benutzername beteiligter Mitarbeiter

        **Spezielle Suchbegriffe**
        * `ist:privat`
        Die Aufgabe ist als `privat` markiert.

        * `ist:niedrig`
        Die Aufgabe hat `niedrige` Priorität.

        * `ist:mittel`
        Die Aufgabe hat `mittlere` Priorität.

        * `ist:hoch`
        Die Aufgabe hat `hohe` Priorität.

        * `ist:neu`
        Die Aufgabe hat den Status `neu`.

        * `ist:in_bearbeitung` oder `ist:ib`
        Die Aufgabe hat den Status `in Bearbeitung`.

        * `ist:erledigt`
        Die Aufgabe hat den Status `erledigt`.

        * `ist:verrechnet`
        Die Aufgabe hat den Verrechnungsstatus `verrechnet`.

        * `ist:nicht_verrechnet` oder `ist:nv`
        Die Aufgabe hat den Verrechnungsstatus `nicht verrechnet`.

        * `ist:garantie`
        Die Aufgabe hat den Verrechnungsstatus `Garantie`.

        * `ist:überfällig`
        Die Aufgabe hat ein Fälligkeitsdatum in der Vergangenheit.

        * `ist:bald_fällig`
        Die Aufgabe hat ein Fälligkeitsdatum in naher Zukunft.

        * `projekt:<Projekt Name>` oder `p:<Projekt Name>`
        Die Aufgabe ist dem Projekt mit dem Namen `<Projekt Name>` zugeordnet.

        * `firma:<Firma Name>` oder `f:<Firma Name>`
        Die Aufgabe ist der Firma mit dem Namen `<Firma Name>` zugeordnet.

        * `verantwortlich:<{{ config('app.name') }} Benutzername>` oder `v:<{{ config('app.name') }} Benutzername>`
        Der Mitarbeiter mit dem {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>` ist für die Aufgabe verantwortlich.

        * `beteiligt:<{{ config('app.name') }} Benutzername>` oder `b:<{{ config('app.name') }} Banutzername>`
        Der Mitarbeiter mit dem {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>` ist an der Aufgabe beteiligt.

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            Material Einkauf
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Aufgaben, in denen sich die Begriffe `Material` und `Einkauf` in dieser Reihenfolge im Namen befinden.
            Hierbei können andere Begriffe vor, zwischen oder nach den Suchbegriffen vorhanden sein.

            Mögliche Ergebnisse
            * Liste Material Einkauf neu
            * Material zum Einkauf
            @endmarkdown
        </div>

        <div class="q-example-input">
            @markdown
            ```
            Material Einkauf v:mst b:aw
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Aufgaben, in denen sich die Begriffe Material und Einkauf in dieser Reihenfolge im Namen befinden,
            der verantwortliche Mitarbeiter den {{ config('app.name') }} Benutzernamen `mst` hat und der Mitarbeiter mit dem {{ config('app.name') }}
            Benutzernamen `aw` an der Aufgabe beteiligt ist.
            @endmarkdown
        </div>

        <div class="q-example-input">
            @markdown
            ```
            ist:überfällig !ist:privat
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Aufgaben, die ein Fälligkeitsdatum in der Vergangenheit haben und nicht als `privat` markiert sind.
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\ConstructionReport::class)
        <a id="bautagesberichte"></a>
        <h4 class="mt-4">Bautagesberichte</h4>

        @markdown
        **Standardattribute**
        * Nummer
        * Name und {{ config('app.name') }} Benutzername beteiligter Mitarbeiter
        * Name und {{ config('app.name') }} Benutzername anwesender Personen
        * Sonstige Besucher
        * Güte- und Funktionsprüfung
        * Fehlende Ausführungsunterlagen
        * Besondere Vorkommnisse
        * Gefahr in Verzug
        * Bedenken
        * Leistungsfortschritt

        **Spezielle Suchbegriffe**
        * `ist:neu`
        Der Bautagesbericht hat den Status `neu`.

        * `ist:unterschrieben` oder `ist:u`
        Der Bautagesbericht hat den Status `unterschrieben`.

        * `ist:erledigt`
        Der Bautagesbericht hat den Status `erledigt`.

        * `nummer:<Nummer>` oder `n:<Nummer>`
        Der Bautagesbericht hat die Nummer `<Nummer>`

        * `projekt:<Projekt Name>` oder `p:<Projekt Name>`
        Der Bautagesbericht ist dem Projekt mit dem Namen `<Projekt Name>` zugeordnet.

        * `firma:<Firma Name>` oder `f:<Firma Name>`
        Der Bautagesbericht ist der Firma mit dem Namen `<Firma Name>` zugeordnet.

        * `techniker:<{{ config('app.name') }} Benutzername>` oder `t:<{{ config('app.name') }} Benutzername>`
        Der Bautagesbericht wurde vom Mitarbeiter mit dem {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>` verfasst.

        * `beteiligt_mitarbeiter:<{{ config('app.name') }} Benutzername>` oder `bm:<{{ config('app.name') }} Benutzername>`
        Der Mitarbeiter mit dem {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>` ist am Bautagesbericht beteiligt.

        * `beteiligt:<Name>` oder `b:<Name>`
        Die Person mit dem Namen `<Name>` ist am Bautagesbericht beteiligt.

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            1
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Bautagesberichte, welche die Nummer `1` besitzen oder sich der Begriff `1` im Kurzbericht befindet.
            Hierbei können andere Begriffe vor oder nach dem Suchbegriff vorhanden sein.

            Mögliche Ergebnisse
            * MTS000000 [Intern] #1
            * MTS000000 [Projektmanagement] #1
            @endmarkdown
        </div>

        <div class="q-example-input">
            @markdown
            ```
            "p:MTS000000 [Intern]" t:aw
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Bautagesberichte, die dem Projekt `MTS000000 [Intern]` zugeordnet sind und vom Mitarbeiter mit dem {{ config('app.name') }}
            Benutzernamen `aw` verfasst wurden.
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\Vehicle::class)
        <a id="fahrzeuge"></a>
        <h4 class="mt-4">Fahrzeuge</h4>

        @markdown
        **Standardattribute**
        * Hersteller
        * Modell
        * Kennzeichen
        * Kommentar

        **Spezielle Suchbegriffe**
        * keine

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            MTS1
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Fahrzeuge, die in Hersteller, Modell,  Kennzeichen oder Kommentar den Begriff `MTS1` aufweisen.

            Mögliche Ergebnisse
            * MT-MTS1
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\FinanceGroup::class)
        <a id="finanzen"></a>
        <h4 class="mt-4">Finanzen</h4>

        @markdown
        **Standardattribute**
        * Name der Finanzgruppe
        * Name des zugeordneten Projektes einer Finanzgruppe
        * Name der Finanzeinträge

        **Spezielle Suchbegriffe**
        * keine

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            ENZ100101
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Finanzgruppen und Einträge, in denen sich die Begriff `ENZ100101` im Namen befindet.
            Hierbei können andere Begriffe vor oder nach den Suchbegriffen vorhanden sein.

            Mögliche Ergebnisse
            * ENZ100101 [Service und Wartung]
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\Company::class)
        <a id="firmen"></a>
        <h4 class="mt-4">Firmen</h4>

        @markdown
        **Standardattribute**
        * Name
        * Name 2
        * Name von zugewiesenen Personen

        **Spezielle Suchbegriffe**
        * `person:<Person Name>` oder `p:<Person Name>`
        Die Person mit dem Namen `<Person Name>` ist der Firma zugeordnet.

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            Management Technik Systeme
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Firmen, in denen sich die Begriff `Management`, `Technik` und `Systeme` in dieser Reihenfolge in
            Name oder Name 2 befinden.
            Hierbei können andere Begriffe vor oder nach den Suchbegriffen vorhanden sein.

            Mögliche Ergebnisse
            * MTS
            * MTS Management Technik Systeme GmbH & CO KG
            @endmarkdown
        </div>
        @endcan

        @if(auth()->user()->can('viewAny', \App\Models\MaterialService::class) || auth()->user()->can('viewAny', \App\Models\WageService::class))
        <a id="leistungen"></a>
        <h4 class="mt-4">Leistungen</h4>

        @markdown
        **Standardattribute**
        * Name

        **Spezielle Suchbegriffe**
        * keine

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            Techniker
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Leistungen, die im Namen den Begriff `Techniker` aufweisen.

            Mögliche Ergebnisse
            * Techniker
            @endmarkdown
        </div>
        @endif

   @can('viewAny', \App\Models\DeliveryNote::class)
        <a id="lieferscheine"></a>
        <h4 class="mt-4">Lieferscheine</h4>

        @markdown
        **Standardattribute**
        * Nummer (Titel)
        * Bemerkungen
        * Name des zugeordneten Projektes
        * Name der Firma, welcher das Projekt zugeordnet ist

        **Spezielle Suchbegriffe**
        * `ist:neu`
        Der Lieferschein hat den Status `neu`.

        * `ist:unterschrieben` oder `ist:u`
        Der Lieferschein hat den Status `unterschrieben`.

        * `ist:erledigt`
        Der Lieferschein hat den Status `erledigt`.

        * `nummer:<Nummer>` oder `n:<Nummer>` oder `titel:<Nummer>` oder `t:<Nummer>`
        Der Lieferschein hat die Nummer `<Nummer>`

        * `projekt:<Projekt Name>` oder `p:<Projekt Name>`
        Der Lieferschein ist dem Projekt mit dem Namen `<Projekt Name>` zugeordnet.

        * `firma:<Firma Name>` oder `f:<Firma Name>`
        Der Lieferschein ist der Firma mit dem Namen `<Firma Name>` zugeordnet.

        * `mitarbeiter:<{{ config('app.name') }} Benutzername>` oder `m:<{{ config('app.name') }} Benutzername>`
        Der Lieferschein wurde vom Mitarbeiter mit dem {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>` verfasst.

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            101595
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Lieferscheine, welche in der Numbmer (Titel) die Zeichenfolge `101595` aufweisen oder sich der Begriff `101595` in den Bemerkungen befindet.
            Hierbei können andere Begriffe vor oder nach dem Suchbegriff vorhanden sein.

            Mögliche Ergebnisse
            * 101595/2023
            @endmarkdown
        </div>

        <div class="q-example-input">
            @markdown
            ```
            "p:MTS000000 [Intern]" m:sst
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Lieferscheine, die dem Projekt `MTS000000 [Intern]` zugeordnet sind und vom Mitarbeiter mit dem {{ config('app.name') }}
            Benutzernamen `sst` verfasst wurden.
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\Employee::class)
        <a id="mitarbeiter"></a>
        <h4 class="mt-4">Mitarbeiter</h4>

        @markdown
        **Standardattribute**
        * Name oder {{ config('app.name') }} Benutzername des Mitarbeiters

        **Spezielle Suchbegriffe**
        * `name:<Mitarbeiter Name>` oder `n:<Mitarbeiter Name>`
        Der Mitarbeiter hat den Namen `<Mitarbeiter Name>`.

        * `benutzer:<{{ config('app.name') }} Benutzername>` oder `b:<{{ config('app.name') }} Benutzername>`
        Der Mitarbeiter hat den {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>`.

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            name:Steiner
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Mitarbeiter, die im Namen den Begriff `Steiner` aufweisen.
            Hierbei können andere Begriffe vor oder nach den Suchbegriffen vorhanden sein.

            Mögliche Ergebnisse
            * Martin Steiner
            * Angelika Steiner
            @endmarkdown
        </div>

        <div class="q-example-input">
            @markdown
            ```
            benutzer: mst
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Mitarbeiter, die den {{ config('app.name') }} Benutzernamen `mst` besitzen.

            Mögliche Ergebnisse
            * Martin Steiner
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\Note::class)
        <a id="notizen"></a>
        <h4 class="mt-4">Notizen</h4>

        @markdown
        **Standardattribute**
        * Titel
        * Kommentar

        **Spezielle Suchbegriffe**
        * keine

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            Telefonnotiz
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Notizen, die im Titel oder Kommentar den Begriff `Telefonnotiz` aufweisen.
            Hierbei können andere Begriffe vor oder nach den Suchbegriffen vorhanden sein.

            Mögliche Ergebnisse
            * Telefonnotiz
            * Telefonnotiz vom 01.01.2000
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\Person::class)
        <a id="personen"></a>
        <h4 class="mt-4">Personen</h4>

        @markdown
        **Standardattribute**
        * Vorname
        * Nachname
        * Abteilung
        * Rolle
        * Firmenname

        **Spezielle Suchbegriffe**
        * `firma:<Firma Name>` oder `f:<Firma Name>`
        Die Person ist der Firma mit dem Namen `<Firma Name>` zugerodnet.

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            Martin Steiner
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Personen, in denen sich die Begriffe `Martin` und `Steiner` in dieser Reihenfolge im Namen befinden.
            Hierbei können andere Begriffe vor, zwischen oder nach den Suchbegriffen vorhanden sein.

            Mögliche Ergebnisse
            * Dr Martin Steiner MEd
            * Martin Steiner
            @endmarkdown
        </div>

        <div class="q-example-input">
            @markdown
            ```
            "f:MTS Management Technik Systeme GmbH & CO KG"
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Personen, die der Firma mit dem Namen `MTS Management Technik Systeme GmbH & CO KG` zugeordnet sind.
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\Project::class)
        <a id="projekte"></a>
        <h4 class="mt-4">Projekte</h4>

        @markdown
        **Standardattribute**
        * Name
        * Firmenname

        **Spezielle Suchbegriffe**
        * `ist:vorphase` oder `ist:vp`
        Das Projekt befindet sich in der Vorphase.

        * `ist:beendet`
        Das Projekt hat ein Enddatum in der Vergangenheit.

        * `ist:infinanzen` oder `ist:if`
        Die Leistungen, welche dem Projekt zugeordnet sind, werden bei den Finanzen berücksichtigt.

        * `firma:<Firma Name>` oder `f:<Firma Name>`
        Das Projekt ist der Firma mit dem Namen `<Firma Name>` zugeordnet.

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            MTS000000
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Projekte, in denen sich der Begriff `MTS000000` im Namen befindet.
            Hierbei können andere Begriffe vor oder nach dem Suchbegriff vorhanden sein.

            Mögliche Ergebnisse
            * MTS000000 [Intern]
            * MTS000000 [Projektmanagement]
            @endmarkdown
        </div>

        <div class="q-example-input">
            @markdown
            ```
            Service "firma:MTS Management Technik Systeme GmbH & CO KG"
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Projekte, in denen sich der Begriff `Service` im Namen befindet und die der Firma mit dem Namen
            `MTS Management Technik Systeme GmbH & CO KG` zugeordnet sind.
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\InspectionReport::class)
        <a id="pruefberichte"></a>
        <h4 class="mt-4">Prüfberichte</h4>

        @markdown
        **Standardattribute**
        * Anlagen-/Gerätenummer
        * Durchgeführte Aufgaben und Bemerkungen
        * Projektname
        * Firmenname des Kunden
        * Name oder {{ config('app.name') }} Benutzername des zuständigen Mitarbeiters

        **Spezielle Suchbegriffe**
        * `ist:neu`
        Der Prüfbericht hat den Status `neu`.

        * `ist:unterschrieben` oder `ist:u`
        Der Prüfbericht hat den Status `unterschrieben`.

        * `ist:erledigt`
        Der Prüfbericht hat den Status `erledigt`.

        * `projekt:<Projekt Name>` oder `p:<Projekt Name>`
        Der Prüfbericht ist dem Projekt mit dem Namen `<Projekt Name>` zugeordnet.

        * `firma:<Firma Name>` oder `f:<Firma Name>`
        Der Prüfbericht ist der Firma mit dem Namen `<Firma Name>` zugeordnet.

        * `techniker:<{{ config('app.name') }} Benutzername>` oder `t:<{{ config('app.name') }} Benutzername>`
        Der Prüfbericht wurde vom Mitarbeiter mit dem {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>` verfasst.

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            1
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Prüfberichte, bei welchen sich der Begriff `1` in der Anlagennummer oder im Kurzbericht befindet.
            Hierbei können andere Begriffe vor oder nach dem Suchbegriff vorhanden sein.

            Mögliche Ergebnisse
            * Anlage 1
            * Anlage 12
            @endmarkdown
        </div>

        <div class="q-example-input">
            @markdown
            ```
            "p:MTS000000 [Intern]" t:aw
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Prüfberichte, die dem Projekt `MTS000000 [Intern]` zugeordnet sind und vom Mitarbeiter mit dem {{ config('app.name') }}
            Benutzernamen `aw` verfasst wurden.
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\FlowMeterInspectionReport::class)
        <a id="pruefberichte_fuer_durchflussmesseinrichtungen"></a>
        <h4 class="mt-4">Prüfberichte für Durchflussmesseinrichtungen</h4>

        @markdown
        **Standardattribute**
        * Anlage
        * Messstelle
        * Sonstige Bemerkungen
        * Projektname
        * Firmenname des Kunden
        * Name oder {{ config('app.name') }} Benutzername des zuständigen Mitarbeiters

        **Spezielle Suchbegriffe**
        * `ist:neu`
        Der Prüfbericht hat den Status `neu`.

        * `ist:unterschrieben` oder `ist:u`
        Der Prüfbericht hat den Status `unterschrieben`.

        * `ist:erledigt`
        Der Prüfbericht hat den Status `erledigt`.

        * `projekt:<Projekt Name>` oder `p:<Projekt Name>`
        Der Prüfbericht ist dem Projekt mit dem Namen `<Projekt Name>` zugeordnet.

        * `firma:<Firma Name>` oder `f:<Firma Name>`
        Der Prüfbericht ist der Firma mit dem Namen `<Firma Name>` zugeordnet.

        * `techniker:<{{ config('app.name') }} Benutzername>` oder `t:<{{ config('app.name') }} Benutzername>`
        Der Prüfbericht wurde vom Mitarbeiter mit dem {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>` verfasst.

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            1
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Prüfberichte, bei welchen sich der Begriff `1` in der Anlage, Messstelle oder Kommentaren befindet.
            Hierbei können andere Begriffe vor oder nach dem Suchbegriff vorhanden sein.

            Mögliche Ergebnisse
            * Anlage 1
            * Messstelle 12
            @endmarkdown
        </div>

        <div class="q-example-input">
            @markdown
            ```
            "p:MTS000000 [Intern]" t:aw
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Prüfberichte, die dem Projekt `MTS000000 [Intern]` zugeordnet sind und vom Mitarbeiter mit dem {{ config('app.name') }}
            Benutzernamen `aw` verfasst wurden.
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\AdditionsReport::class)
        <a id="regieberichte"></a>
        <h4 class="mt-4">Regieberichte</h4>

        @markdown
        **Standardattribute**
        * Nummer
        * Name oder {{ config('app.name') }} Benutzername beteiligter Mitarbeiter
        * Name anwesender Personen
        * Sonstige Besucher
        * Güte- und Funktionsprüfung
        * Fehlende Ausführungsunterlagen
        * Besondere Vorkommnisse
        * Gefahr in Verzug
        * Bedenken
        * Leistungsfortschritt
        * Projektname
        * Firmenname

        **Spezielle Suchbegriffe**
        * `ist:neu`
        Der Regiebericht hat den Status `neu`.

        * `ist:unterschrieben` oder `ist:u`
        Der Regiebericht hat den Status `unterschrieben`.

        * `ist:erledigt`
        Der Regiebericht hat den Status `erledigt`.

        * `nummer:<Nummer>` oder `n:<Nummer>`
        Der Regiebericht hat die Nummer `<Nummer>`

        * `projekt:<Projekt Name>` oder `p:<Projekt Name>`
        Der Regiebericht ist dem Projekt mit dem Namen `<Projekt Name>` zugeordnet.

        * `techniker:<{{ config('app.name') }} Benutzername>` oder `t:<{{ config('app.name') }} Benutzername>`
        Der Regiebericht wurde vom Mitarbeiter mit dem {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>` verfasst.

        * `beteiligt_mitarbeiter:<{{ config('app.name') }} Benutzername>` oder `bm:<{{ config('app.name') }} Benutzername>`
        Der Mitarbeiter mit dem {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>` ist am Regiebericht beteiligt.

        * `beteiligt:<Name>` oder `b:<Name>`
        Die Person mit dem Namen `<Name>` ist am Regiebericht beteiligt.

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            1
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Regieberichte, welche die Nummer `1` besitzen oder sich der Begriff `1` im Kurzbericht befindet.
            Hierbei können andere Begriffe vor oder nach dem Suchbegriff vorhanden sein.

            Mögliche Ergebnisse
            * MTS000000 [Intern] #1
            * MTS000000 [Projektmanagement] #1
            @endmarkdown
        </div>

        <div class="q-example-input">
            @markdown
            ```
            "p:MTS000000 [Intern]" t:aw
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Regieberichte, die dem Projekt `MTS000000 [Intern]` zugeordnet sind und vom Mitarbeiter mit dem {{ config('app.name') }}
            Benutzernamen `aw` verfasst wurden.
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \Spatie\Permission\Models\Role::class)
        <a id="rollen"></a>
        <h4 class="mt-4">Rollen</h4>

        @markdown
        **Standardattribute**
        * Name

        **Spezielle Suchbegriffe**
        * keine

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            Administrator
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Rollen, die im Namen den Begriff `Administrator` aufweisen.

            Mögliche Ergebnisse
            * Administrator
            @endmarkdown
        </div>
        @endcan

        @can('viewAny', \App\Models\ServiceReport::class)
        <a id="serviceberichte"></a>
        <h4 class="mt-4">Serviceberichte</h4>

        @markdown
        **Standardattribute**
        * Nummer
        * Kurzbericht
        * Projektname
        * Firmenname
        * Name oder {{ config('app.name') }} Benutzername des zuständigen Mitarbeiters

        **Spezielle Suchbegriffe**
        * `ist:neu`
        Der Servicebericht hat den Status `neu`.

        * `ist:unterschrieben` oder `ist:u`
        Der Servicebericht hat den Status `unterschrieben`.

        * `ist:erledigt`
        Der Servicebericht hat den Status `erledigt`.

        * `nummer:<Nummer>` oder `n:<Nummer>`
        Der Servicebericht hat die Nummer `<Nummer>`

        * `projekt:<Projekt Name>` oder `p:<Projekt Name>`
        Der Servicebericht ist dem Projekt mit dem Namen `<Projekt Name>` zugeordnet.

        * `firma:<Firma Name>` oder `f:<Firma Name>`
        Der Servicebericht ist der Firma mit dem Namen `<Firma Name>` zugeordnet.

        * `techniker:<{{ config('app.name') }} Benutzername>` oder `t:<{{ config('app.name') }} Benutzername>`
        Der Servicebericht ist dem Mitarbeiter mit dem {{ config('app.name') }} Benutzernamen `<{{ config('app.name') }} Benutzername>` zugeordnet.

        **Beispiele**
        @endmarkdown

        <div class="q-example-input">
            @markdown
            ```
            1
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Serviceberichte, die die Nummer `1` besitzen oder sich der Begriff `1` im Kurzbericht befindet.
            Hierbei können andere Begriffe vor oder nach dem Suchbegriff vorhanden sein.

            Mögliche Ergebnisse
            * MTS000000 [Intern] #1
            * MTS000000 [Projektmanagement] #1
            @endmarkdown
        </div>

        <div class="q-example-input">
            @markdown
            ```
            "p:MTS000000 [Intern]" t:aw
            ```
            @endmarkdown
        </div>
        <div class="q-example-output">
            @markdown
            Filtert Serviceberichte, die dem Projekt `MTS000000 [Intern]` und dem Mitarbeiter mit dem {{ config('app.name') }}
            Benutzernamen `aw` zugeordnet sind.
            @endmarkdown
        </div>
        @endcan

        </div>
    </div>
@endsection
