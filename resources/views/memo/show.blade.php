@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('memo.breadcrumb')

            <h3>
                Aktenvermerk
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $memo->title }}
                    @if(false)
                        <svg class="feather feather-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('memos.edit', $memo) }}">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                    </svg>
                    Bearbeiten
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('memos.email', ['memo' => $memo, 'redirect' => 'show']) }}">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                    </svg>
                    Email versenden
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('memos.download', $memo) }}" target="_blank">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                    </svg>
                    PDF erstellen
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                <form action="{{ route('memos.destroy', $memo) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                        </svg>
                        Entfernen
                    </button>
                </form>
            </div>

        </div>
    </div>

    <div class="container my-4">
        @include ('memo.show_overview')
    </div>
@endsection
