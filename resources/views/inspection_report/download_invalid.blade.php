@extends('layouts.app')

@section('content')
    <div class="q-container">
        <div class="q-card text-center" style="padding: 2.75rem 1.5rem;">
            <img class="empty-state" src="{{ asset('svg/done.svg') }}" alt="kein Prüfbericht" />
            <p class="lead text-muted mt-2 mb-0">
                Unter diesem Link ist kein Prüfbericht zum Herunterladen vorhanden.<br />
                Der Prüfbericht wurde wahrscheinlich bereits heruntergeladen oder er existiert nicht.
            </p>
        </div>
    </div>
@endsection
