@extends('layouts.app')

@section('content')
    <div class="q-container">
        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#book"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Notiz anlegen</div>
                    <h1 class="q-title">Neue Notiz</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" enctype="multipart/form-data" action="{{ route('notes.store') }}" method="post" novalidate>
            @include('note.fields', ['note' => $note])

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('notes.index') }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#save"></use></svg>
                    Notiz speichern
                </button>
            </div>
        </form>
    </div>
@endsection
