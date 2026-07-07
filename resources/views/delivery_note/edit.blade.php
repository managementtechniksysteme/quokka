@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('delivery_note.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#package"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Lieferschein bearbeiten</div>
                    <h1 class="q-title">{{ $deliveryNote->title }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" enctype="multipart/form-data" action="{{ route('delivery-notes.update', $deliveryNote) }}" method="post" novalidate>
            @method('PATCH')
            @include('delivery_note.fields', [ 'deliveryNote' => $deliveryNote, 'currentProject' => $currentProject, 'projects' => $projects ])

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('delivery-notes.show', $deliveryNote) }}"><svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use></svg>
                    Lieferschein speichern
                </button>
            </div>
        </form>
    </div>
@endsection
