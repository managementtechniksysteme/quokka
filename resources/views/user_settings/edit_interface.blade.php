@extends('user_settings.edit')

@section('tab')
    <form class="q-form needs-validation" action="{{ route('user-settings.update-interface') }}" method="post" novalidate>
        @csrf

        <div class="q-form-section">
            <div class="q-form-section__head">
                Darstellung von Listen
                <div class="q-form-section__desc">
                    Standardeinstellungen zur Darstellung von Listen.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <div>
                    <label>Anzahl der Listenelemente pro Seite</label>
                    <div class="btn-group @error('list_pagination_size') is-invalid @enderror">
                        @foreach(['5', '10', '15', '20', '25', '30'] as $size)
                            <input type="radio" class="btn-check" name="list_pagination_size" id="list_pagination_size-{{ $size }}" value="{{ $size }}" autocomplete="off" @if(old('list_pagination_size', optional(Auth::user()->settings)->list_pagination_size) == $size) checked @endif>
                            <label class="btn btn-outline-secondary" for="list_pagination_size-{{ $size }}">{{ $size }}</label>
                        @endforeach
                    </div>
                    <div class="invalid-feedback @error('list_pagination_size') d-block @enderror">
                        @error('list_pagination_size') {{ $message }} @enderror
                    </div>
                </div>

                @can('tools-viewlatestchanges')
                    <div>
                        <label>Anzahl der Elemente auf der Übersichtsseite für letzte Änderungen</label>
                        <div class="btn-group @error('latest_changes_quantity') is-invalid @enderror">
                            @foreach(['5', '10', '15', '20', '25', '30', '40', '50'] as $qty)
                                <input type="radio" class="btn-check" name="latest_changes_quantity" id="latest_changes_quantity-{{ $qty }}" value="{{ $qty }}" autocomplete="off" @if(old('latest_changes_quantity', optional(Auth::user()->settings)->latest_changes_quantity) == $qty) checked @endif>
                                <label class="btn btn-outline-secondary" for="latest_changes_quantity-{{ $qty }}">{{ $qty }}</label>
                            @endforeach
                        </div>
                        <div class="invalid-feedback @error('latest_changes_quantity') d-block @enderror">
                            @error('latest_changes_quantity') {{ $message }} @enderror
                        </div>
                    </div>
                @endcan

                <div>
                    <label>Erledigte Elemente (z.B. Aufgaben) in Listen anzeigen?</label>
                    <div class="btn-group @error('show_finished_items') is-invalid @enderror">
                        <input type="radio" class="btn-check" name="show_finished_items" id="show_finished_items-1" value="1" autocomplete="off" @if(old('show_finished_items', optional(Auth::user()->settings)->show_finished_items) == true) checked @endif>
                        <label class="btn btn-outline-secondary" for="show_finished_items-1">anzeigen</label>
                        <input type="radio" class="btn-check" name="show_finished_items" id="show_finished_items-0" value="0" autocomplete="off" @if(old('show_finished_items', optional(Auth::user()->settings)->show_finished_items) == false) checked @endif>
                        <label class="btn btn-outline-secondary" for="show_finished_items-0">ausblenden</label>
                    </div>
                    <div class="invalid-feedback @error('show_finished_items') d-block @enderror">
                        @error('show_finished_items') {{ $message }} @enderror
                    </div>
                </div>

                <div>
                    <label>Unterschriebene Berichte in Listen anzeigen?</label>
                    <div class="btn-group @error('show_signed_reports') is-invalid @enderror">
                        <input type="radio" class="btn-check" name="show_signed_reports" id="show_signed_reports-1" value="1" autocomplete="off" @if(old('show_signed_reports', optional(Auth::user()->settings)->show_signed_reports) == true) checked @endif>
                        <label class="btn btn-outline-secondary" for="show_signed_reports-1">anzeigen</label>
                        <input type="radio" class="btn-check" name="show_signed_reports" id="show_signed_reports-0" value="0" autocomplete="off" @if(old('show_signed_reports', optional(Auth::user()->settings)->show_signed_reports) == false) checked @endif>
                        <label class="btn btn-outline-secondary" for="show_signed_reports-0">ausblenden</label>
                    </div>
                    <div class="invalid-feedback @error('show_signed_reports') d-block @enderror">
                        @error('show_signed_reports') {{ $message }} @enderror
                    </div>
                </div>

                <div>
                    <label>Nur eigene Berichte in Listen anzeigen?</label>
                    <div class="btn-group @error('show_only_own_reports') is-invalid @enderror">
                        <input type="radio" class="btn-check" name="show_only_own_reports" id="show_only_own_reports-1" value="1" autocomplete="off" @if(old('show_only_own_reports', optional(Auth::user()->settings)->show_only_own_reports) == true) checked @endif>
                        <label class="btn btn-outline-secondary" for="show_only_own_reports-1">nur eigene</label>
                        <input type="radio" class="btn-check" name="show_only_own_reports" id="show_only_own_reports-0" value="0" autocomplete="off" @if(old('show_only_own_reports', optional(Auth::user()->settings)->show_only_own_reports) == false) checked @endif>
                        <label class="btn btn-outline-secondary" for="show_only_own_reports-0">alle anzeigen</label>
                    </div>
                    <div class="invalid-feedback @error('show_only_own_reports') d-block @enderror">
                        @error('show_only_own_reports') {{ $message }} @enderror
                    </div>
                </div>

                @can('projects.view.estimates')
                    <div>
                        <label>Kostenindikatoren in Projektlisten anzeigen?</label>
                        <div class="btn-group @error('show_cost_estimates') is-invalid @enderror">
                            <input type="radio" class="btn-check" name="show_cost_estimates" id="show_cost_estimates-1" value="1" autocomplete="off" @if(old('show_cost_estimates', optional(Auth::user()->settings)->show_cost_estimates) == true) checked @endif>
                            <label class="btn btn-outline-secondary" for="show_cost_estimates-1">anzeigen</label>
                            <input type="radio" class="btn-check" name="show_cost_estimates" id="show_cost_estimates-0" value="0" autocomplete="off" @if(old('show_cost_estimates', optional(Auth::user()->settings)->show_cost_estimates) == false) checked @endif>
                            <label class="btn btn-outline-secondary" for="show_cost_estimates-0">ausblenden</label>
                        </div>
                        <div class="invalid-feedback @error('show_cost_estimates') d-block @enderror">
                            @error('show_cost_estimates') {{ $message }} @enderror
                        </div>
                    </div>
                @endcan
            </div>
        </div>

        <div class="q-form-section">
            <div class="q-form-section__head">
                Sortierung von Kommentaren
                <div class="q-form-section__desc">
                    Anzeigereihenfolge von Kommentaren in Aufgaben.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <div>
                    <label>Sortierreihenfolge</label>
                    <div class="btn-group @error('task_comments_sort_newest_first') is-invalid @enderror">
                        <input type="radio" class="btn-check" name="task_comments_sort_newest_first" id="task_comments_sort_newest_first-1" value="1" autocomplete="off" @if(old('task_comments_sort_newest_first', optional(Auth::user()->settings)->task_comments_sort_newest_first) == true) checked @endif>
                        <label class="btn btn-outline-secondary" for="task_comments_sort_newest_first-1">Neuere zuerst</label>
                        <input type="radio" class="btn-check" name="task_comments_sort_newest_first" id="task_comments_sort_newest_first-0" value="0" autocomplete="off" @if(old('task_comments_sort_newest_first', optional(Auth::user()->settings)->task_comments_sort_newest_first) == false) checked @endif>
                        <label class="btn btn-outline-secondary" for="task_comments_sort_newest_first-0">Ältere zuerst</label>
                    </div>
                    <div class="invalid-feedback @error('task_comments_sort_newest_first') d-block @enderror">
                        @error('task_comments_sort_newest_first') {{ $message }} @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="q-form-section">
            <div class="q-form-section__head">
                Abrechnung
                <div class="q-form-section__desc">
                    Ob Zeilen in der Abrechnungstabelle automatisch aufgeklappt werden, und wie viele vergangene Tage standardmäßig gefiltert werden.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <div>
                    <label>Automatisches Anzeigen von Fehlern</label>
                    <div class="btn-group @error('accounting_expand_errors') is-invalid @enderror">
                        <input type="radio" class="btn-check" name="accounting_expand_errors" id="accounting_expand_errors-1" value="1" autocomplete="off" @if(old('accounting_expand_errors', optional(Auth::user()->settings)->accounting_expand_errors) == true) checked @endif>
                        <label class="btn btn-outline-secondary" for="accounting_expand_errors-1">automatisch anzeigen</label>
                        <input type="radio" class="btn-check" name="accounting_expand_errors" id="accounting_expand_errors-0" value="0" autocomplete="off" @if(old('accounting_expand_errors', optional(Auth::user()->settings)->accounting_expand_errors) == false) checked @endif>
                        <label class="btn btn-outline-secondary" for="accounting_expand_errors-0">nicht anzeigen</label>
                    </div>
                    <div class="invalid-feedback @error('accounting_expand_errors') d-block @enderror">
                        @error('accounting_expand_errors') {{ $message }} @enderror
                    </div>
                </div>

                <div>
                    <label for="accounting_filter_default_days">Anzahl der letzten Tage für den Standardfilter</label>
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

        <div class="q-form-actions">
            <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                Einstellungen speichern
            </button>
        </div>
    </form>
@endsection
