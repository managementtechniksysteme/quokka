@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-center mt-4">
            <img class="empty-state" src="{{ asset('svg/done.svg') }}" alt="done" />
            <p class="lead text-muted mt-1">Unter diesem Link ist kein Prüfbericht zum Herunterladen vorhanden.<br />
                Der Prüfbericht wurde wahrscheinlich bereits heruntergeladen oder er existiert nicht.</p>
        </div>
    </div>
@endsection
