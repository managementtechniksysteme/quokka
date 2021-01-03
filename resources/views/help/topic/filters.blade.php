@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('help.breadcrumb')

            <h3>
                Hilfe
                <small class="text-muted">Markdown</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <a id="top"></a>

        @markdown
        1. [Adressen](#adressen)
        2. [Aktenvermerke](#aktenvermerke)
        3. [Aufgaben](#aufgaben)
        4. [Firmen](#firmen)
        5. [Mitarbeiter](#mitarbeiter)
        6. [Personen](#personen)
        7. [Projekte](#projekte)
        8. [Serviceberichte](#serviceberichte)
        @endmarkdown

        @markdown
        Die in Quokka implementierte Suche zum Filtern von Listen kann mit speziellen Suchbegriffen verwendet werden.
        Diese filtern Attribute nach angegebenen Werten. Somit ist eine genauere Suche möglich. Die Verwendung dieser
        Begriffe ist kontextabhängig.

        Die verwendbaren Begriffe werden im Folgenden beschrieben.
        @endmarkdown

        <div class="alert alert-info">
            <div class="d-flex align-items-center">
                <svg class="feather feather-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                </svg>
                <b>Hinweis</b>
            </div>

            <p class="m-0">
                Alle Suchbegriffe, einem speziellen Begriff entsprechen, werden durch eine logische <em>UND</em> Verknüpfung
                ausgewertet. Sind alle Schlagwörter in einem Attribut vorhanden, wird das jeweilige Objekt in die
                Ergebnisliste aufgenommen. Durch Anführen eines speziellen Suchbegriffs mit einem Rufzeichen (!) wird
                der Suchbegriff negiert.
            </p>
            <p  class="m-0">
                Die Suche in Standardattributen (siehe folgender Hinweis) wird ebenfalls durch
                eine logische <em>UND</em> Verknüpfung angehängt.
            </p>
        </div>

        <div class="alert alert-info">
            <div class="d-flex align-items-center">
                <svg class="feather feather-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                </svg>
                <b>Hinweis</b>
            </div>

            <p class="m-0">
                Alle Suchbegriffe, die nicht einem speziellen Begriff entsprechen, werden in den kontextabhängigen
                Standardattributen gesucht. Hierbei wird eine logische <em>ODER</em> Verknüpfung verwendet. Sind alle
                Schlagwörter in einem Attribut vorhanden, wird das jeweilige Objekt in die Ergebnisliste aufgenommen.
                Groß- und Kleinschreibung wird bei der Suche ignoriert.
            </p>

        </div>

        <a id="adressen"></a>
        <h4 class="mt-4">Adressen</h4>

        @markdown
        **Standardattribute**
        * Name
        * Straße und Nummer
        * Postleitzahl
        * Ort

        **Spezielle Suchbegriffe**
        * keine

        **Beispiele**
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            MTS
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Adressen, in denen sich der Begriff `MTS` in Name, Straße und Nummer, Postleizahl oder Ort befindet.
            Hierbei können andere Begriffe vor oder nach dem Suchbegriff vorhanden sein.

            Mögliche Ergebnisse
            * MTS
            * MTS Management Technik Systeme GmbH & CO KG
            @endmarkdown
        </div>

        <a id="aktenvermerke"></a>
        <h4 class="mt-4">Aktenvermerke</h4>

        @markdown
        **Standardattribute**
        * Titel

        **Spezielle Suchbegriffe**
        * `hat:folgetermin`  
        Der Aktenvermerk at einen Folgetermin in der Zukunft.
        
        * `nummer:<Nummer>` oder `n:<Nummer>`  
        Der Aktenvermerk hat die Nummer `<Nummer>`.
                    
        * `projekt:<Projekt Name>` oder `p:<Projekt Name>`  
        Der Aktenvermerk ist dem Projekt mit dem Namen `<Projekt Name>` zugeordnet.
                        
        * `von:<Quokka Benutzername>`  
        Der Aktenvermerk wurde vom Mitarbeiter mit dem Quokka Benutzernamen `<Quokka Benutzername>` verfasst.
        
        * `an:<Name>`  
        Der Aktenvermerk wurde an die Person mit dem Namen `<Name>` verfasst.
                                            
        * `beteiligt:<Name>` oder `b:<Name>`  
        Die Person mit dem Namen `<Name>` ist am Aktenvermerk beteiligt.
        
        * `beteiligt_mitarbeiter:<Quokka Benutzername>` oder `bm:<Quokka Benutzername>`  
        Der Mitarbeiter mit dem Quokka Benutzernamen `<Quokka Benutzername>` ist am Aktenvermerk beteiligt.
        
        * `verständigt:<Name>` oder `v:<Name>`  
        Die Person mit dem Namen `<Name>` wird über dem Aktenvermerk verständigt.
        
        * `verständigt_mitarbeiter:<Quokka Benutzername>` oder `vm:<Quokka Benutzername>`  
        Der Mitarbeiter mit dem Quokka Benutzernamen `<Quokka Benutzername>` wird über dem Aktenvermerk verständigt.
        

        **Beispiele**
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            Besprechung zur Inbetriebnahme
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Aktenvermerke, in denen sich die Begriffe `Besprechung`, `zur` und `Inbetriebnahme` in dierser 
            Reihenfolge im Titel befinden.
            Hierbei können andere Begriffe vor, zwischen oder nach den Suchbegriffen vorhanden sein.
    
            Mögliche Ergebnisse
            * Besprechung zur Inbetriebnahme
            * MTS Besprechung zur Inbetriebnahme der neuen Kaffeemaschine.
            @endmarkdown
        </div>

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            Besprechung zur Inbetriebnahme von:mst "an:Angelika Steiner"
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Aktenvermerke, in denen sich die Begriffe `Besprechung`, `zur` und `Inbetriebnahme` in dieser
            Reiehnfolge im Titel befinden, der Verfasser den Quokka Benutzernamen `mst` hat und der Empfänger den Namen 
            `Angelika Steiner` hat.
            @endmarkdown
        </div>

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            "p:MTS000000 [Intern]" !hat:folgetermin
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Aktenvermerke, die dem Projekt mit dem Namen `MTS000000 [Intern]` zugeordnet sind und keine
            Folgetermine in der Zukunft aufweisen.
            @endmarkdown
        </div>

        <a id="aufgaben"></a>
        <h4 class="mt-4">Aufgaben</h4>

        @markdown
        **Standardattribute**
        * Name

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
                    
        * `verantwortlich:<Quokka Benutzername>` oder `v:<Quokka Benutzername>`  
        Der Mitarbeiter mit dem Quokka Benutzernamen `<Quokka Benutzername>` ist für die Aufgabe verantwortlich.
                                
        * `beteiligt:<Quokka Benutzername>` oder `b:<Quokka Banutzername>`  
        Der Mitarbeiter mit dem Quokka Benutzernamen `<Quokka Benutzername>` ist an der Aufgabe beteiligt.

        **Beispiele**
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            Material Einkauf
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Aufgaben, in denen sich die Begriffe `Material` und `Einkauf` in dieser Reihenfolge im Namen befinden.
            Hierbei können andere Begriffe vor, zwischen oder nach den Suchbegriffen vorhanden sein.

            Mögliche Ergebnisse
            * Liste Material Einkauf neu
            * Material zum Einkauf
            @endmarkdown
        </div>

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            Material Einkauf v:mst b:aw
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Aufgaben, in denen sich die Begriffe Material und Einkauf in dieser Reihenfolge im Namen befinden,
            der verantwortliche Mitarbeiter den Quokka Benutzernamen `mst` hat und der Mitarbeiter mit dem Quokka
            Benutzernamen `aw` an der Aufgabe beteiligt ist.
            @endmarkdown
        </div>

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            ist:überfällig !ist:privat
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Aufgaben, die ein Fälligkeitsdatum in der Vergangenheit haben und nicht als `privat` markiert sind.
            @endmarkdown
        </div>

        <a id="firmen"></a>
        <h4 class="mt-4">Firmen</h4>

        @markdown
        **Standardattribute**
        * Name
        * Name 2

        **Spezielle Suchbegriffe**
        * keine

        **Beispiele**
        @endmarkdown
                                                                                                                                    
        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            Management Technik Systeme
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Firmen, in denen sich die Begriff `Management`, `Technik` und `Systeme` in dieser Reihenfolge in 
            Name oder Name 2 befinden.
            Hierbei können andere Begriffe vor oder nach den Suchbegriffen vorhanden sein.

            Mögliche Ergebnisse
            * MTS
            * MTS Management Technik Systeme GmbH & CO KG
            @endmarkdown
        </div>

        <a id="mitarbeiter"></a>
        <h4 class="mt-4">Mitarbeiter</h4>

        @markdown
        **Standardattribute**
        * keine

        **Spezielle Suchbegriffe**
        * `name:<Mitarbeiter Name>` oder `n:<Mitarbeiter Name>`  
        Der Mitarbeiter hat den Namen `<Mitarbeiter Name>`.
        
        * `benutzer:<Quokka Benutzername>` oder `b:<Quokka Benutzername>`  
        Der Mitarbeiter hat den Quokka Benutzernamen `<Quokka Benutzername>`.

        **Beispiele**
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            name:Steiner
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Mitarbeiter, die im Namen den Begriff `Steiner` aufweisen.
            Hierbei können andere Begriffe vor oder nach den Suchbegriffen vorhanden sein.

            Mögliche Ergebnisse
            * Martin Steiner
            * Angelika Steiner
            @endmarkdown
        </div>

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            benutzer: mst
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Mitarbeiter, die den Quokka Benutzernamen `mst` besitzen.
        
            Mögliche Ergebnisse
            * Martin Steiner
            @endmarkdown
        </div>
                                            
        <a id="personen"></a>
        <h4 class="mt-4">Personen</h4>

        @markdown
        **Standardattribute**
        * Vorname
        * Nachname
        * Abteilung
        * Rolle

        **Spezielle Suchbegriffe**
        * `firma:<Firma Name>` oder `f:<Firma Name>`  
        Die Person ist der Firma mit dem Namen `<Firma Name>` zugerodnet.

        **Beispiele**
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            Martin Steiner
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Personen, in denen sich die Begriffe `Martin` und `Steiner` in dieser Reihenfolge im Namen befinden.
            Hierbei können andere Begriffe vor, zwischen oder nach den Suchbegriffen vorhanden sein.

            Mögliche Ergebnisse
            * Dr Martin Steiner MEd
            * Martin Steiner
            @endmarkdown
        </div>

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            "f:MTS Management Technik Systeme GmbH & CO KG"
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Personen, die der Firma mit dem Namen `MTS Management Technik Systeme GmbH & CO KG` zugeordnet sind.
            @endmarkdown
        </div>
                                                                                                                                                
        <a id="projekte"></a>
        <h4 class="mt-4">Projekte</h4>

        @markdown
        **Standardattribute**
        * Name

        **Spezielle Suchbegriffe**
        * `ist:beendet`  
        Das Projekt hat ein Enddatum in der Vergangenheit.
        
        * `firma:<Firma Name>` oder `f:<Firma Name>`  
        Das Projekt ist der Firma mit dem Namen `<Firma Name>` zugeordnet.

        **Beispiele**
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            MTS000000
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Projekte, in denen sich der Begriff `MTS000000` im Namen befindet.
            Hierbei können andere Begriffe vor oder nach dem Suchbegriff vorhanden sein.

            Mögliche Ergebnisse
            * MTS000000 [Intern]
            * MTS000000 [Projektmanagement]
            @endmarkdown
        </div>

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            Service "firma:MTS Management Technik Systeme GmbH & CO KG"
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Projekte, in denen sich der Begriff `Service` im Namen befindet und die der Firma mit dem Namen
            `MTS Management Technik Systeme GmbH & CO KG` zugeordnet sind.
            @endmarkdown
        </div>

        <a id="serviceberichte"></a>
        <h4 class="mt-4">Serviceberichte</h4>

        @markdown
        **Standardattribute**
        * Nummer
        * Kurzbericht

        **Spezielle Suchbegriffe**
        * `ist:neu`  
        Der Servicebericht hat den Status `neu`.
        
        * `ist:unterschrieben` oder `ist:u`  
        Der Servicebericht hat den Status `unterschrieben`.
        
        * `ist:erledigt`  
        Der Servicebericht hat den Status `erledigt`.
        
        * `nummber:<Nummer>` oder `n:<Nummer>`  
        Der Servicebericht hat die Nummer `<Nummer>`
                    
        * `projekt:<Projekt Name>` oder `p:<Projekt Name>`  
        Der Servicebericht ist dem Projekt mit dem Namen `<Projekt Name>` zugeordnet.
                                
        * `techniker:<Quokka Benutzername>` oder `t:<Quokka Benutzername>`  
        Der Servicebericht ist dem Mitarbeiter mit dem Quokka Benutzernamen `<Quokka Benutzername>` zugeordnet.        

        **Beispiele**
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            1
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Serviceberichte, die die Nummer `1` besitzen oder sich der Begriff `1` im Kurzbericht befindet.
            Hierbei können andere Begriffe vor oder nach dem Suchbegriff vorhanden sein.

            Mögliche Ergebnisse
            * MTS000000 [Intern] #1
            * MTS000000 [Projektmanagement] #1
            @endmarkdown
        </div>

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            "p:MTS000000 [Intern]" t:aw
            ```
            @endmarkdown
        </div>
        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Filtert Serviceberichte, die dem Projekt `MTS000000 [Intern]` und dem Mitarbeiter mit dem Quokka 
            Benutzernamen `aw` zugeordnet sind.
            @endmarkdown
        </div>
                                            
    </div>
@endsection
