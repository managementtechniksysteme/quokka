@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('note.breadcrumb')

            <h3>
                Notiz bearbeiten
                <small class="text-muted">{{ $note->created_at->format('d.m.Y, H:i') }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" enctype="multipart/form-data" action="{{ route('notes.update', $note) }}" method="post" novalidate>
            @method('PATCH')
            @component('note.fields', [ 'note' => $note ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Notiz speichern
            </button>
        </form>
    </div>
@endsection
