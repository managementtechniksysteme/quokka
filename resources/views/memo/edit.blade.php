@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('memo.breadcrumb')

            <h3>
                Aktenvermerk bearbeiten
                <small class="text-muted">{{ $memo->title }}</small>
            </h3>
        </div>
    </div>

    <div class="container mt-4">
        <form class="needs-validation mt-4" enctype="multipart/form-data" action="{{ route('memos.update', $memo) }}" method="post" novalidate>
            @method('PATCH')
            @component('memo.fields', [ 'memo' => $memo, 'currentProject' => $currentProject, 'projects' => $projects, 'currentEmployeeComposer' => $currentEmployeeComposer, 'employees' => $employees, 'currentPersonRecipient' => $currentPersonRecipient, 'currentPresentPeople' => $currentPresentPeople, 'currentNotifiedPeople' => $currentNotifiedPeople, 'people' => $people, 'currentAttachments' => $currentAttachments ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Aktenvermerk speichern
            </button>
        </form>
    </div>
@endsection
