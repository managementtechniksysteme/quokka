@extends('user_settings.edit')

@section('tab')
    <form class="needs-validation" action="{{ route('user-settings.update-interface') }}" method="post" novalidate>

        @csrf

        <div class="row">
            <div class="col">
                <p class="text-muted d-inline-flex align-items-center mb-1">
                    <svg class="icon icon-16 me-2">
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

                <div class="mb-3">
                    <div>
                        <label for="list_pagination_size">Anzahl der Listenelemente pro Seite</label>
                    </div>
                    <div class="btn-group @error('list_pagination_size') is-invalid @enderror" >
                        <input type="radio" class="btn-check" name="list_pagination_size" id="list_pagination_size-5" value="5" autocomplete="off" @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '5') checked @endif>
                        <label class="btn btn-outline-secondary" for="list_pagination_size-5">5</label>
                        <input type="radio" class="btn-check" name="list_pagination_size" id="list_pagination_size-10" value="10" autocomplete="off" @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '10') checked @endif>
                        <label class="btn btn-outline-secondary" for="list_pagination_size-10">10</label>
                        <input type="radio" class="btn-check" name="list_pagination_size" id="list_pagination_size-15" value="15" autocomplete="off" @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '15') checked @endif>
                        <label class="btn btn-outline-secondary" for="list_pagination_size-15">15</label>
                        <input type="radio" class="btn-check" name="list_pagination_size" id="list_pagination_size-20" value="20" autocomplete="off" @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '20') checked @endif>
                        <label class="btn btn-outline-secondary" for="list_pagination_size-20">20</label>
                        <input type="radio" class="btn-check" name="list_pagination_size" id="list_pagination_size-25" value="25" autocomplete="off" @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '25') checked @endif>
                        <label class="btn btn-outline-secondary" for="list_pagination_size-25">25</label>
                        <input type="radio" class="btn-check" name="list_pagination_size" id="list_pagination_size-30" value="30" autocomplete="off" @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == '30') checked @endif>
                        <label class="btn btn-outline-secondary" for="list_pagination_size-30">30</label>
                    </div>
                    <div class="invalid-feedback @error('list_pagination_size') d-block @enderror">
                        @error('list_pagination_size')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                @can('tools-viewlatestchanges')
                    <div class="mb-3">
                        <div>
                            <label for="latest_changes_quantity">Anzahl der Elemente auf der Übersichtsseite für letzte Änderungen</label>
                        </div>
                        <div class="btn-group @error('latest_changes_quantity') is-invalid @enderror" >
                            <input type="radio" class="btn-check" name="latest_changes_quantity" id="latest_changes_quantity-5" value="5" autocomplete="off" @if(old('latest_changes_quantity', optional(Auth::user()->settings)->latest_changes_quantity) == '5') checked @endif>
                            <label class="btn btn-outline-secondary" for="latest_changes_quantity-5">5</label>
                            <input type="radio" class="btn-check" name="latest_changes_quantity" id="latest_changes_quantity-10" value="10" autocomplete="off" @if(old('latest_changes_quantity', optional(Auth::user()->settings)->latest_changes_quantity) == '10') checked @endif>
                            <label class="btn btn-outline-secondary" for="latest_changes_quantity-10">10</label>
                            <input type="radio" class="btn-check" name="latest_changes_quantity" id="latest_changes_quantity-15" value="15" autocomplete="off" @if(old('latest_changes_quantity', optional(Auth::user()->settings)->latest_changes_quantity) == '15') checked @endif>
                            <label class="btn btn-outline-secondary" for="latest_changes_quantity-15">15</label>
                            <input type="radio" class="btn-check" name="latest_changes_quantity" id="latest_changes_quantity-20" value="20" autocomplete="off" @if(old('latest_changes_quantity', optional(Auth::user()->settings)->latest_changes_quantity) == '20') checked @endif>
                            <label class="btn btn-outline-secondary" for="latest_changes_quantity-20">20</label>
                            <input type="radio" class="btn-check" name="latest_changes_quantity" id="latest_changes_quantity-25" value="25" autocomplete="off" @if(old('latest_changes_quantity', optional(Auth::user()->settings)->latest_changes_quantity) == '25') checked @endif>
                            <label class="btn btn-outline-secondary" for="latest_changes_quantity-25">25</label>
                            <input type="radio" class="btn-check" name="latest_changes_quantity" id="latest_changes_quantity-30" value="30" autocomplete="off" @if(old('latest_changes_quantity', optional(Auth::user()->settings)->latest_changes_quantity) == '30') checked @endif>
                            <label class="btn btn-outline-secondary" for="latest_changes_quantity-30">30</label>
                            <input type="radio" class="btn-check" name="latest_changes_quantity" id="latest_changes_quantity-40" value="40" autocomplete="off" @if(old('latest_changes_quantity', optional(Auth::user()->settings)->latest_changes_quantity) == '40') checked @endif>
                            <label class="btn btn-outline-secondary" for="latest_changes_quantity-40">40</label>
                            <input type="radio" class="btn-check" name="latest_changes_quantity" id="latest_changes_quantity-50" value="50" autocomplete="off" @if(old('latest_changes_quantity', optional(Auth::user()->settings)->latest_changes_quantity) == '50') checked @endif>
                            <label class="btn btn-outline-secondary" for="latest_changes_quantity-50">50</label>
                        </div>
                        <div class="invalid-feedback @error('latest_changes_quantity') d-block @enderror">
                            @error('latest_changes_quantity')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                @endcan

                <div class="mb-3">
                    <div>
                        <label for="show_finished_items">Erledigte Elemente (z.B. Aufgaben) in Listen anzeigen?</label>
                    </div>
                    <div class="btn-group @error('show_finished_items') is-invalid @enderror" >
                        <input type="radio" class="btn-check" name="show_finished_items" id="show_finished_items-1" value="1" autocomplete="off" @if(old('show_finished_items', optional(Auth::user()->settings)->show_finished_items) == true) checked @endif>
                        <label class="btn btn-outline-secondary" for="show_finished_items-1">Elemente anzeigen</label>
                        <input type="radio" class="btn-check" name="show_finished_items" id="show_finished_items-0" value="0" autocomplete="off" @if(old('show_finished_items', optional(Auth::user()->settings)->show_finished_items) == false) checked @endif>
                        <label class="btn btn-outline-secondary" for="show_finished_items-0">Elemente nicht anzeigen</label>
                    </div>
                    <div class="invalid-feedback @error('show_finished_items') d-block @enderror">
                        @error('show_finished_items')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <div>
                        <label for="show_signed_reports">Unterschriebene Elemente in Listen von Berichten anzeigen?</label>
                    </div>
                    <div class="btn-group @error('show_signed_reports') is-invalid @enderror" >
                        <input type="radio" class="btn-check" name="show_signed_reports" id="show_signed_reports-1" value="1" autocomplete="off" @if(old('show_signed_reports', optional(Auth::user()->settings)->show_signed_reports) == true) checked @endif>
                        <label class="btn btn-outline-secondary" for="show_signed_reports-1">Elemente anzeigen</label>
                        <input type="radio" class="btn-check" name="show_signed_reports" id="show_signed_reports-0" value="0" autocomplete="off" @if(old('show_signed_reports', optional(Auth::user()->settings)->show_signed_reports) == false) checked @endif>
                        <label class="btn btn-outline-secondary" for="show_signed_reports-0">Elemente nicht anzeigen</label>
                    </div>
                    <div class="invalid-feedback @error('show_signed_reports') d-block @enderror">
                        @error('show_signed_reports')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <div>
                        <label for="show_finished_items">Nur eigene Elemente in Listen von Berichten anzeigen?</label>
                    </div>
                    <div class="btn-group @error('show_only_own_reports') is-invalid @enderror" >
                        <input type="radio" class="btn-check" name="show_only_own_reports" id="show_only_own_reports-1" value="1" autocomplete="off" @if(old('show_only_own_reports', optional(Auth::user()->settings)->show_only_own_reports) == true) checked @endif>
                        <label class="btn btn-outline-secondary" for="show_only_own_reports-1">Nur eigene Elemente anzeigen</label>
                        <input type="radio" class="btn-check" name="show_only_own_reports" id="show_only_own_reports-0" value="0" autocomplete="off" @if(old('show_only_own_reports', optional(Auth::user()->settings)->show_only_own_reports) == false) checked @endif>
                        <label class="btn btn-outline-secondary" for="show_only_own_reports-0">Auch andere Elemente anzeigen</label>
                    </div>
                    <div class="invalid-feedback @error('show_only_own_reports') d-block @enderror">
                        @error('show_only_own_reports')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                @can('projects.view.estimates')
                    <div class="mb-3">
                        <div>
                            <label for="show_finished_items">Kostenindikatoren in Projektlisten anzeigen?</label>
                        </div>
                        <div class="btn-group @error('show_cost_estimates') is-invalid @enderror" >
                            <input type="radio" class="btn-check" name="show_cost_estimates" id="show_cost_estimates-1" value="1" autocomplete="off" @if(old('show_cost_estimates', optional(Auth::user()->settings)->show_cost_estimates) == true) checked @endif>
                            <label class="btn btn-outline-secondary" for="show_cost_estimates-1">Kostenindikatoren anzeigen</label>
                            <input type="radio" class="btn-check" name="show_cost_estimates" id="show_cost_estimates-0" value="0" autocomplete="off" @if(old('show_cost_estimates', optional(Auth::user()->settings)->show_cost_estimates) == false) checked @endif>
                            <label class="btn btn-outline-secondary" for="show_cost_estimates-0">Kostenindikatoren nicht anzeigen</label>
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
                    <svg class="icon icon-16 me-2">
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
                <div class="mb-3">
                    <div>
                        <label for="show_finished_items">Sortierreihenfolge von Kommentaren</label>
                    </div>
                    <div class="btn-group @error('task_comments_sort_newest_first') is-invalid @enderror" >
                        <input type="radio" class="btn-check" name="task_comments_sort_newest_first" id="task_comments_sort_newest_first-1" value="1" autocomplete="off" @if(old('task_comments_sort_newest_first', optional(Auth::user()->settings)->task_comments_sort_newest_first) == true) checked @endif>
                        <label class="btn btn-outline-secondary" for="task_comments_sort_newest_first-1">Neuere zuerst</label>
                        <input type="radio" class="btn-check" name="task_comments_sort_newest_first" id="task_comments_sort_newest_first-0" value="0" autocomplete="off" @if(old('task_comments_sort_newest_first', optional(Auth::user()->settings)->task_comments_sort_newest_first) == false) checked @endif>
                        <label class="btn btn-outline-secondary" for="task_comments_sort_newest_first-0">Ältere zuerst</label>
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
                    <svg class="icon icon-16 me-2">
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

                <div class="mb-3">
                    <div>
                        <label for="accounting_expand_errors">Automatisches Anzeigen von Fehlern</label>
                    </div>
                    <div class="btn-group @error('accounting_expand_errors') is-invalid @enderror" >
                        <input type="radio" class="btn-check" name="accounting_expand_errors" id="accounting_expand_errors-1" value="1" autocomplete="off" @if(old('accounting_expand_errors', optional(Auth::user()->settings)->accounting_expand_errors) == true) checked @endif>
                        <label class="btn btn-outline-secondary" for="accounting_expand_errors-1">Probleme automatisch anzeigen</label>
                        <input type="radio" class="btn-check" name="accounting_expand_errors" id="accounting_expand_errors-0" value="0" autocomplete="off" @if(old('accounting_expand_errors', optional(Auth::user()->settings)->accounting_expand_errors) == false) checked @endif>
                        <label class="btn btn-outline-secondary" for="accounting_expand_errors-0">Probleme nicht automatisch anzeigen</label>
                    </div>
                    <div class="invalid-feedback @error('accounting_expand_errors') d-block @enderror">
                        @error('accounting_expand_errors')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
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
                    <svg class="icon icon-16 me-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                    </svg>
                    Einstellungen speichern
                </button>
            </div>
        </div>

    </form>

@endsection
