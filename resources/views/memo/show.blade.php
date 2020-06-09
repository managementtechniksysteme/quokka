@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>
            Aktenvermerk
            <small class="text-muted">{{ $memo->title }}</small>
        </h3>

        <a class="btn btn-primary d-inline-flex align-items-center" href="{{ route('memos.edit', $memo) }}">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
            </svg>
            Aktenvermerk bearbeiten
        </a>
        <a class="btn btn-outline-warning d-none d-xl-inline-flex align-items-center" href="#">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
            </svg>
            Aktenvermerk zu Favoriten hinzufügen
        </a>
        <a class="btn btn-outline-secondary d-none d-xl-inline-flex align-items-center" href="#">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
            </svg>
            PDF des Aktenvermerkes erstellen
        </a>
        <form action="{{ route('memos.destroy', $memo) }}" method="post" class="d-none d-xl-inline">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                </svg>
                Aktenvermerk entfernen
            </button>
        </form>

        <div class="dropdown d-inline d-xl-none">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownActionsButton" data-toggle="dropdown">
                Weitere Aktionen
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Aktenvermerk zu Favoriten hinzufügen
                </a>
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                    </svg>
                    PDF des Aktenvermerkes erstellen
                </a>
                <form action="{{ route('memos.destroy', $memo) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                        </svg>
                        Aktenvermerk entfernen
                    </button>
                </form>
            </div>
        </div>

        @include ('memo.show_overview')

    </div>
@endsection
