@extends('user_settings.edit')

@section('tab')
    <form class="needs-validation" action="{{ route('user-settings.update-interface') }}" method="post" novalidate>

        @csrf

        <div class="row">
            <div class="col">
                <p class="text-muted d-inline-flex align-items-center mb-1">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#list"></use>
                    </svg>
                    Darstellung von Listen
                </p>
                <p class="text-muted">
                    Hier werden Standardeinstellungen zur Darstellung von Listen gesetzt.
                </p>
            </div>
        </div>

        <div class="row">

            <div class="col">

                <div class="form-group">
                    <div>
                        <label for="list_pagination_size">Anzahl der Listenelemente pro Seite</label>
                    </div>
                    <div class="btn-group btn-group-toggle @error('list_pagination_size') is-invalid @enderror" data-toggle="buttons">
                        <label class="btn btn-outline-secondary @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '5') active @endif">
                            <input type="radio" name="list_pagination_size" id="5" value="5" autocomplete="off" @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '5') checked @endif> 5
                        </label>
                        <label class="btn btn-outline-secondary @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '10') active @endif">
                            <input type="radio" name="list_pagination_size" id="10" value="10" autocomplete="off" @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '10') checked @endif> 10
                        </label>
                        <label class="btn btn-outline-secondary @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '15') active @endif">
                            <input type="radio" name="list_pagination_size" id="15" value="15" autocomplete="off" @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '15') checked @endif> 15
                        </label>
                        <label class="btn btn-outline-secondary @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '20') active @endif">
                            <input type="radio" name="list_pagination_size" id="20" value="20" autocomplete="off" @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '20') checked @endif> 20
                        </label>
                        <label class="btn btn-outline-secondary @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '25') active @endif">
                            <input type="radio" name="list_pagination_size" id="25" value="25" autocomplete="off" @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '25') checked @endif> 25
                        </label>
                        <label class="btn btn-outline-secondary @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '30') active @endif">
                            <input type="radio" name="list_pagination_size" id="30" value="30" autocomplete="off" @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '30') checked @endif> 30
                        </label>
                    </div>
                    <div class="invalid-feedback @error('list_pagination_size') d-block @enderror">
                        @error('list_pagination_size')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label for="show_finished_items">Erledigte Elemente (z.B. Aufgaben) in Listen anzeigen?</label>
                    </div>
                    <div class="btn-group btn-group-toggle @error('show_finished_items') is-invalid @enderror" data-toggle="buttons">
                        <label class="btn btn-outline-secondary @if(old('show_finished_items', optional(Auth::user()->settings)->show_finished_items) == true) active @endif">
                            <input type="radio" name="show_finished_items" id="1" value="1" autocomplete="off" @if(old('show_finished_items', optional(Auth::user()->settings)->show_finished_items) == true) checked @endif> Elemente anzeigen
                        </label>
                        <label class="btn btn-outline-secondary @if(old('show_finished_items', optional(Auth::user()->settings)->show_finished_items) == false) active @endif">
                            <input type="radio" name="show_finished_items" id="0" value="0" autocomplete="off" @if(old('show_finished_items', optional(Auth::user()->settings)->show_finished_items) == false) checked @endif> Elemente nicht anzeigen
                        </label>
                    </div>
                    <div class="invalid-feedback @error('show_finished_items') d-block @enderror">
                        @error('show_finished_items')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label for="show_finished_items">Nur eigene Elemente in Listen von Berichten anzeigen?</label>
                    </div>
                    <div class="btn-group btn-group-toggle @error('show_only_own_reports') is-invalid @enderror" data-toggle="buttons">
                        <label class="btn btn-outline-secondary @if(old('show_only_own_reports', optional(Auth::user()->settings)->show_only_own_reports) == true) active @endif">
                            <input type="radio" name="show_only_own_reports" id="1" value="1" autocomplete="off" @if(old('show_only_own_reports', optional(Auth::user()->settings)->show_only_own_reports) == true) checked @endif> Nur eigene Elemente anzeigen
                        </label>
                        <label class="btn btn-outline-secondary @if(old('show_only_own_reports', optional(Auth::user()->settings)->show_only_own_reports) == false) active @endif">
                            <input type="radio" name="show_only_own_reports" id="0" value="0" autocomplete="off" @if(old('show_only_own_reports', optional(Auth::user()->settings)->show_only_own_reports) == false) checked @endif> Auch andere Elemente anzeigen
                        </label>
                    </div>
                    <div class="invalid-feedback @error('show_only_own_reports') d-block @enderror">
                        @error('show_only_own_reports')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                @can('projects.view.estimates')
                    <div class="form-group">
                        <div>
                            <label for="show_finished_items">Kostenindikatoren in Projektlisten anzeigen?</label>
                        </div>
                        <div class="btn-group btn-group-toggle @error('show_cost_estimates') is-invalid @enderror" data-toggle="buttons">
                            <label class="btn btn-outline-secondary @if(old('show_cost_estimates', optional(Auth::user()->settings)->show_cost_estimates) == true) active @endif">
                                <input type="radio" name="show_cost_estimates" id="1" value="1" autocomplete="off" @if(old('show_cost_estimates', optional(Auth::user()->settings)->show_cost_estimates) == true) checked @endif> Kostenindikatoren anzeigen
                            </label>
                            <label class="btn btn-outline-secondary @if(old('show_cost_estimates', optional(Auth::user()->settings)->show_cost_estimates) == false) active @endif">
                                <input type="radio" name="show_cost_estimates" id="0" value="0" autocomplete="off" @if(old('show_cost_estimates', optional(Auth::user()->settings)->show_cost_estimates) == false) checked @endif> Kostenindikatoren nicht anzeigen
                            </label>
                        </div>
                        <div class="invalid-feedback @error('show_cost_estimates') d-block @enderror">
                            @error('show_cost_estimates')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                @endcan

            </div>

        </div>

        <div class="row">
            <div class="col">
                <p class="text-muted d-inline-flex align-items-center mb-1 mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                    </svg>
                    Sortierung von Kommentaren in Aufgaben
                </p>
                <p class="text-muted">
                    Die Anzeigereihenfolge von Kommentaren in Listen kann hier angepasst werden.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <div>
                        <label for="show_finished_items">Sortierreihenfolge von Kommentaren</label>
                    </div>
                    <div class="btn-group btn-group-toggle @error('task_comments_sort_newest_first') is-invalid @enderror" data-toggle="buttons">
                        <label class="btn btn-outline-secondary @if(old('task_comments_sort_newest_first', optional(Auth::user()->settings)->task_comments_sort_newest_first) == true) active @endif">
                            <input type="radio" name="task_comments_sort_newest_first" id="1" value="1" autocomplete="off" @if(old('task_comments_sort_newest_first', optional(Auth::user()->settings)->task_comments_sort_newest_first) == true) checked @endif> Neuere zuerst
                        </label>
                        <label class="btn btn-outline-secondary @if(old('task_comments_sort_newest_first', optional(Auth::user()->settings)->task_comments_sort_newest_first) == false) active @endif">
                            <input type="radio" name="task_comments_sort_newest_first" id="0" value="0" autocomplete="off" @if(old('task_comments_sort_newest_first', optional(Auth::user()->settings)->task_comments_sort_newest_first) == false) checked @endif> Ältere zuerst
                        </label>
                    </div>
                    <div class="invalid-feedback @error('task_comments_sort_newest_first') d-block @enderror">
                        @error('task_comments_sort_newest_first')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <p class="text-muted d-inline-flex align-items-center mb-1 mt-4">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                    </svg>
                    Einstellungen zur Anzeige bei der Abrechnung
                </p>
                <p class="text-muted">
                    Hier kann eingestellt werden, ob Zeilen in der Tabelle auf der Abrechnungsseite automatisch
                    aufgeklappt werden. Dies ermöglicht, Probleme mit der jeweiligen Zeile beim Speichern rasch zu
                    einzusehen. Weiters kann die Anzahl der letzten Tage für die Standardfilterung eingestellt werden.
                </p>
            </div>
        </div>

        <div class="row">

            <div class="col">

                <div class="form-group">
                    <div>
                        <label for="accounting_expand_errors">Automatisches Anzeigen von Fehlern</label>
                    </div>
                    <div class="btn-group btn-group-toggle @error('accounting_expand_errors') is-invalid @enderror" data-toggle="buttons">
                        <label class="btn btn-outline-secondary @if(old('accounting_expand_errors', optional(Auth::user()->settings)->accounting_expand_errors) == true) active @endif">
                            <input type="radio" name="accounting_expand_errors" id="1" value="1" autocomplete="off" @if(old('accounting_expand_errors', optional(Auth::user()->settings)->accounting_expand_errors) == true) checked @endif> Probleme automatisch anzeigen
                        </label>
                        <label class="btn btn-outline-secondary @if(old('accounting_expand_errors', optional(Auth::user()->settings)->accounting_expand_errors) == false) active @endif">
                            <input type="radio" name="accounting_expand_errors" id="0" value="0" autocomplete="off" @if(old('accounting_expand_errors', optional(Auth::user()->settings)->accounting_expand_errors) == false) checked @endif> Probleme nicht automatisch anzeigen
                        </label>
                    </div>
                    <div class="invalid-feedback @error('accounting_expand_errors') d-block @enderror">
                        @error('accounting_expand_errors')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="accounting_filter_default_days">Anzahl der letzten Tage für den Standard Filter</label>
                    <input type="number" min="1" class="form-control @error('accounting_filter_default_days') is-invalid @enderror" id="accounting_filter_default_days" name="accounting_filter_default_days" placeholder="3" value="{{ old('accounting_filter_default_days', Auth::user()->settings->accounting_filter_default_days) }}" />
                    <div class="invalid-feedback @error('accounting_filter_default_days') d-block @enderror">
                        @error('accounting_filter_default_days')
                            {{ $message }}
                        @else
                            Anzahl der Tage muss mindestens 1 sein.
                        @enderror
                    </div>
                </div>

            </div>

        </div>

        <div class="row mt-4">
            <div class="col">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                    </svg>
                    Einstellungen speichern
                </button>
            </div>
        </div>

    </form>

@endsection
