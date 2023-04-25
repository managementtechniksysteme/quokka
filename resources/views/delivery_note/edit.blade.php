@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('delivery_note.breadcrumb')

            <h3>
                Lieferschein bearbeiten
                <small class="text-muted">{{ $deliveryNote->title }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" enctype="multipart/form-data" action="{{ route('delivery-notes.update', $deliveryNote) }}" method="post" novalidate>
            @method('PATCH')
            @component('delivery_note.fields', [ 'deliveryNote' => $deliveryNote, 'currentProject' => $currentProject, 'projects' => $projects ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Lieferschein speichern
            </button>
        </form>
    </div>
@endsection
