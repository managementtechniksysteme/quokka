@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('role.breadcrumb')

            <h3>
                Rolle bearbeiten
                <small class="text-muted">{{ $role->name }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <div class="alert alert-warning mt-1" role="alert">
            <div class="d-inline-flex align-items-center">
                <svg class="feather feather-24 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                </svg>
                Hier gesetzte Berechtigungen wirken sich nur auf die Vorlage (Rolle) aus. Benutzern, welche die
                geänderten Berechtigungen der gespeicherten Rolle erhalten sollen, muss diese Rolle erneut einmalig
                zugewiesen werden. Benutzer sind direkt mit Berechtigungen, nicht mit Rollen, verknüpft!
            </div>
        </div>

        <form class="needs-validation mt-4" action="{{ route('roles.update', $role) }}" method="post" novalidate>
            @method('PATCH')
            @component('role.fields', [ 'role' => $role ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Rolle speichern
            </button>
        </form>
    </div>
@endsection
