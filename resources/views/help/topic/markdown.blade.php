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
        1. [Überschriften](#ueberschriften)
        2. [Paragrapphen und Zeilenumbrüche](#paragraphen_und_zeilenumbrueche)
        3. [Fetter, kursiver und durchgestrichener Text](#fetter_kursiver_und_durchgestrichener_text)
        4. [Zitate](#zitate)
        5. [Aufzählungen, Nummerierungen und Listen von Erledigungen](#aufzaehlungen_nummerierungen_und_listen_von_erledigungen)
        6. [Links und Email Adressen](#links_und_email_adressen)
        7. [Bilder](#bilder)
        8. [Tabellen](#tabellen)
        9. [Horizontale Linien](#horizontale_linien)
        10. [Quelltext und Hervorheben von Text im Fließtext (Inline Quelltext), Text ohne Formatierung](#quelltext_und_hervorheben_von_text_in_fliesstext_inline_quelltext_text_ohne_formatierung)
        @endmarkdown

        @markdown
        Der in Quokka implementierte Markdown Editor unterztützt eine Reihe von Textmanipulationen. Diese sind im
        Folgenden beschrieben. Die wichtigsten Möglichkeiten zur Veränderung von Text sind des weiteren über
        die Aktionsleiste des Editors aufrufbar.
        @endmarkdown

        <div class="alert alert-info">
            <div class="d-flex align-items-center">
                <svg class="feather feather-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                </svg>
                <b>Hinweis</b>
            </div>

            <p class="m-0">
                Die Darstellung von Elemementen in der Anwendung kann sich von jener in generierten PDF Dokumenten
                teilweise unterscheiden. Manche Elemente werden in PDF Dokumenten gänzlich ignoriert. Hierzu werden
                in den einzelnen Abschnitten Hinweise angegeben.
            </p>

        </div>

        <a id="ueberschriften"></a>
        <h4 class="mt-4">Überschriften</h4>

        @markdown
        Überschriften werden durch das Rautensymbol (`#`), gefolgt von einem Leerzeichen eingeleitet. Durch verwenden
        von mehreren Rautensymbolen hintereinander können Überschriften unterschiedlicher Größe erzeugt werden (je
        mehr Rautensymbole, desto kleiner die Überschrift).
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            # Überschrift 1
            ## Überschrift 2
            ### Überschrift 3
            #### Überschrift 4
            ##### Überschrift 5
            ###### Überschrift 6
            ```
            @endmarkdown
        </div>

        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            # Überschrift 1
            ## Überschrift 2
            ### Überschrift 3
            #### Überschrift 4
            ##### Überschrift 5
            ###### Überschrift 6
            @endmarkdown
        </div>


        @markdown
        Überschriften der ersten zwei Ebenen können  alternativ
        durch Gleichheitszeichen (`=`) beziehungsweise Bindestriche (`-`) in der nächsten Zeile erzeugt werden.
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            Überschrift 1
            ==============
            Überschrift 2
            --------------
            ```
            @endmarkdown
        </div>

        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Überschrift 1
            ==============
            Überschrift 2
            --------------
            @endmarkdown
        </div>

        <a id="paragraphen_und_zeilenumbrueche"></a>
        <h4 class="mt-4">Paragraphen und Zeilenumbrüche</h4>

        @markdown
        Paragraphen werden durch eine leere Zeile getrennt. Um einen Zeilenumbruch einzufügen, können entweder zwei
        oder mehr Leerzeichen oder ein Backslash (`\`) an das Ende der Zeile vor dem Umbruch angehängt werden.
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            Dieser Text befindet sich im ersten Paragraph.
            Auch dieser Text ist Teil des ersten Paragraphes. Er wird direkt an den vorherigen Text angehängt.

            Dieser Text leitet einen zweiten Paragraph ein.

            Dieser Text ist durch einen\
            Zeilenumbruch getrennt. Alternativ können zwei Leerzeichen am Ende der vorigen Zeile verwendet werden.
            ```
            @endmarkdown
        </div>

        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Dieser Text befindet sich im ersten Paragraph.
            Auch dieser Text ist Teil des ersten Paragraphes. Er wird direkt an den vorherigen Text angehängt.

            Dieser Text leitet einen zweiten Paragraph ein.

            Dieser Text ist durch einen\
            Zeilenumbruch getrennt. Alternativ können zwei Leerzeichen am Ende der vorigen Zeile verwendet werden.
            @endmarkdown
        </div>

        <a id="fetter_kursiver_und_durchgestrichener_text"></a>
        <h4 class="mt-4">Fetter, kursiver und durchgestrichener Text</h4>
        @markdown
        Um Text fett darzustellen, kann er entweder mit zwei Sternsymbolen (`**`) oder zwei Unterstrichen (`__`)
        umschlossen werden. Kursiver Text kann mittels umschließen von einem Sternsymbol (`*`) oder Unterstrich (`_`)
        erzeugt werden. Um Text durchgestrichen erscheinen zu lassen, kann er in zwei Tildesymbole (`~~`) eingeschlossen
        werden. Die Manipulationsmöglichkeiten können beliebig kombiniert werden.
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            **fett**, __ebenfalls fett__
            *kursiv*, _ebenfalls kursiv_
            ***kombiniert***
            ~~durchgestrichen~~
            ```
            @endmarkdown
        </div>

        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            **fett**, __ebenfalls fett__\
            *kursiv*, _ebenfalls kursiv_\
            ***kombiniert***\
            ~~durchgestrichen~~
            @endmarkdown
        </div>

        <a id="zitate"></a>
        <h4 class="mt-4">Zitate</h4>

        @markdown
        Zur Darstellung eines Zitates wird jede Zeile mit einem Größer Symbol (`>`), gefolgt von einem Leerzeichen,
        begonnen. Zwischen zwei Paragraphen muß eine Zeile mit dem Größer Symbol (`>`) eingefügt werden. Um Zitate
        zu verschachteln, werden mehrere Größer Symbole (`>>`) hintereinander gereiht.
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            > Ein Zitat
            >
            > bestehend aus zwei Paragraphen
            >> und einem verschateltem Zitat.
            ```
            @endmarkdown
        </div>

        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            > Ein Zitat
            >
            > bestehend aus zwei Paragraphen
            >> und einem verschateltem Zitat.
            @endmarkdown
        </div>

        <a id="aufzaehlungen_nummerierungen_und_listen_von_erledigungen"></a>
        <h4 class="mt-4">Aufzählungen, Nummerierungen und Listen von Erledigungen</h4>

        @markdown
        Um eine Aufzählung zu erstellen, muss jeder Aufzählungspunkt in einer neün Zeile begonnen und durch
        ein Sternsymbol (`*`), einen Bindestrich (`-`) oder ein Plus Symbol (`+`), gefolgt von einem Leerzeichen,
        angeführt werden. Durch Einrücken um vier Leerzeichen oder einem Tabulator werden untergeordnete Listen
        erstellt. Innerhalb eines Aufzählungspunktes können weitere Elemente, wie Paragraphen oder Zitate, durch
        umschließen von leeren Zeilen eingefügt werden. Es ist zu beachten, dass die weiteren Elemente mit vier
        Leerzeichen oder einem Tabulator einzurücken sind.
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            Beispiel 1

            - eine
            - Aufzählung
            - mit einer
            - untergeordneten Aufzählung
            - und drei Elementen

            Beispiel 2

            * andere
            * Aufzählungszeichen

            Beispiel 3

            * ein

                Paragraph

            * in einem Element
            ```
            @endmarkdown
        </div>

        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Beispiel 1

            - eine
            - Aufzählung
                - mit einer
                - untergeordneten Aufzählung
            - und drei Elementen

            Beispiel 2

            * andere
            * Aufzählungszeichen

            Beispiel 3

            * ein

                Paragraph

            * in einem Element
            @endmarkdown
        </div>

        @markdown
        Nummerierungen werden analog zu Aufzählungen angelegt. Jedoch werden einzelne Elemente durch eine Nummmer,
        gefolgt von einem Punkt (`1.`) und einem Leerzeichen eingeleitet. Die Nummerierung muss nicht durchgängig in
        seqüntieller Reihung erfolgen.
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            Beispiel 1

            1. eine
            2. Nummerierung
            1. mit einer
            2. untergeordneten Nummerierung
            3. und drei Elementen

            Beispiel 2

            1. andere
            1. Nummerierung

            Beispiel 3

            1. ein

                Paragraph

            2. in einem Element
            ```
            @endmarkdown
        </div>

        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Beispiel 1

            1. eine
            2. Nummerierung
                1. mit einer
                2. untergeordneten Nummerierung
            3. und drei Elementen

            Beispiel 2

            1. andere
            1. Nummerierung

            Beispiel 3

            1. ein

                Paragraph

            2. in einem Element
            @endmarkdown
        </div>

        @markdown
        Listen mit zu erledigenden Elementen werden analog zu Aufzählungen angelegt. Jedoch werden statt dem
        Aufzählungszeichen eckige Klammern mit einem Leerzeichen als Inhalt (`- [ ]`) für eine noch nicht erledigte
        Aufgabe beziehungsweise einem x (`- [x]`) oder X (`- [X]`) für eine erledigte Aufgabe angeführt.
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            Beispiel 1

            - [ ] nicht erledigt
            - [x] erledigt
            - [X] ebenfalls erledigt

            Beispiel 2

            * [ ] nicht erledigt
            * [x] erledigt
            ```
            @endmarkdown
        </div>

        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Beispiel 1

            - [ ] nicht erledigt
            - [x] erledigt
            - [X] ebenfalls erledigt

            Beispiel 2

            * [ ] nicht erledigt
            * [x] erledigt
            @endmarkdown
        </div>

        <a id="links_und_email_adressen"></a>
        <h4 class="mt-4">Links und Email Adressen</h4>

        @markdown
        Ein Link oder eine Email Adresse wird durch den anzuzeigenden Text in eckigen Klammern (`[Text]`), gefolgt von
        der URL beziehungsweise Adresse in runden Klammern (`(URL)`) eingefügt. Falls kein seperater Text angezeigt
        werden soll, kann eine Link oder Email Adresse erstellt werden, indem die URL beziehungsweise Adresse durch
        Kleiner und Größer Symbol umschlossen wird (`<URL>`). Alternativ kann die URL oder Email Adresse alleine
        eingefügt werden, Es wird daraus automatisch ein Link erstellt.
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            Ein Link zu [Google](https://google.com) und eine Email zu [Max Mustermann](max.mustermann@example.com).

            Ebenfalls ein Link zu <https://google.com> und eine Email zu <max.mustermann@example.com>.

            Ein automatisch erkannter Link zu https://google.com und eine Email zu max.mustermann@example.com.
            ```
            @endmarkdown
        </div>

        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            Ein Link zu [Google](https://google.com) und eine Email zu [Max Mustermann](max.mustermann@example.com).

            Ebenfalls ein Link zu <https://google.com> und eine Email zu <max.mustermann@example.com>.

            Ein automatisch erkannter Link zu https://google.com und eine Email zu max.mustermann@example.com.
            @endmarkdown
        </div>

        <a id="bilder"></a>
        <h4 class="mt-4">Bilder</h4>

        @markdown
        Um ein Bild einzufügen, ist ein Ausrufezeichen, gefolgt von einer alternativen Bildbeschreibung in eckigen
        Klammern (`[Beschreibung]`) sowie der URL zum gewünschten Bild in runden Klammern (`(URL)`) einzugeben.
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            ![Ein Bild einer Katze](https://placekitten.com/300/200)
            ```
            @endmarkdown
        </div>

        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            ![Ein Bild einer Katze](https://placekitten.com/300/200)
            @endmarkdown
        </div>

        <a id="tabellen"></a>
        <h4 class="mt-4">Tabellen</h4>

        @markdown
        Tabellen werden aufgebaut, indem Spalten durch senkrechte Striche (`|`) getrennt werden. Der Tabellenkopf wird
        vom Inhalt durch Bindestriche (`-`) getrennt. Zwischen einzelnen Inhaltszeilen ist keine Trennung erforderlich.
        Durch das Einfügen von Doppelpunkten (`:`) in der Trennzeile kann die Ausrichtung der jeweiligen Spalte
        bestimmt werden. Ein Doppelpunkt am Beginn bedeutet linksbündig, Doppelpunkte am Beginn und Ende bedeuten
        mittig und ein Doppelpunkt am Ende richtet den Inhalt rechtsbündig aus. Die Formatierung der Tabelle, so dass
        der Inhalt vertikal und horizontal ausgerichtet wird, ist optional.
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            erste Spalte | zweite Spalte (mittig) | dritte Spalte (rechtsbündig)
            -------------|:----------------------:|-----------------------------:
            erste        | Zeile                  | 1
            zweite       | Zeile                  | 2
            [eine Tabelle mit Beschriftung]

            erste Spalte | zweite Spalte (mittig) | dritte Spalte (rechtsbündig)
            -|:-:|-:
            erste | Zeile | 1
            zweite | Zeile | 2
            ```
            @endmarkdown
        </div>

        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            erste Spalte | zweite Spalte (mittig) | dritte Spalte (rechtsbündig)
            -------------|:----------------------:|-----------------------------:
            erste        | Zeile                  | 1
            zweite       | Zeile                  | 2
            [eine Tabelle mit Beschriftung]

            erste Spalte | zweite Spalte (mittig) | dritte Spalte (rechtsbündig)
            -|:-:|-:
            erste | Zeile | 1
            zweite | Zeile | 2
            @endmarkdown
        </div>

        <a id="horizontale_linien"></a>
        <h4 class="mt-4">Horizontale Linien</h4>

        @markdown
        Um eine horizontale Linie anzuzeigen, können drei oder mehr Stern Symbole (`***`), Bindestriche (`---`) oder
        Unterstriche (`___`) auf einer eigenen Linie angeführt werden.
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            ***
            ---
            ___
            ```
            @endmarkdown
        </div>

        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            ***
            ---
            ___
            @endmarkdown
        </div>

        <a id="quelltext_und_hervorheben_von_text_in_fliesstext_inline_quelltext_text_ohne_formatierung"></a>
        <h4 class="mt-4">Quelltext und Hervorheben von Text im Fließtext (Inline Quelltext), Text ohne Formatierung</h4>

        @markdown
        Um Quelltext darzustellen, wird dieser von jeweils drei Backticks (`` ``` ``) in einer eigenen Zeile vor und nach
        dem Quelltext umschlossen. Dieser Text wird wie eingegeben, ohne Formatierung abgebildet. Alternativ kann
        Quelltext in jeder Zeile um vier Leerzeichen oder einen Tabulator eingerückt werden. Um Text innerhalb eines
        Fließtextes hervorzuheben, wird dieser in einzelne Backticks (`` ` ``) eingeschlossen.
        @endmarkdown

        <div class="markdown-example-input bg-light border border-bottom-0 p-2">
            @markdown
            ```
            ```
            Mehrzeiliger
            Quelltext
            ohne Formatierung
            ```

                Ebenfalls mehrzeiliger
                Quelltext
                ohne Formatierung

            Hervorheben von `Quelltext` innerhalb eines Fließtextes.
            ```
            @endmarkdown
        </div>

        <div class="markdown-example-output border mb-2 p-2">
            @markdown
            ```
            Mehrzeiliger
            Quelltext
            ohne Formatierung
            ```

                Ebenfalls mehrzeiliger
                Quelltext
                ohne Formatierung

            Hervorheben von `Quelltext` innerhalb eines Fließtextes.
            @endmarkdown
        </div>

    </div>
@endsection
