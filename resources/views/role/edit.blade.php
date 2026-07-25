@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('roles.show', $role) }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $role->name }}</span>
@endsection

@section('content')
    <div class="q-container">
        <div class="d-none d-md-block">
            @include('role.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#key"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Rolle bearbeiten</div>
                    <h1 class="q-title">{{ $role->name }}</h1>
                </div>
            </div>
        </div>

        <div class="q-banner">
            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
            <span>Hier gesetzte Berechtigungen wirken sich nur auf die Vorlage (Rolle) aus. Benutzern, welche die geänderten Berechtigungen der gespeicherten Rolle erhalten sollen, muss diese Rolle erneut einmalig zugewiesen werden. Benutzer sind direkt mit Berechtigungen, nicht mit Rollen, verknüpft!</span>
        </div>

        <form class="q-form needs-validation" action="{{ route('roles.update', $role) }}" method="post" novalidate>
            @method('PATCH')
            @include('role.fields', ['role' => $role])

            <div class="q-form-actions q-form-actions--solo-mobile">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ route('roles.show', $role) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Rolle speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
