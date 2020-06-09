@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Aktenvermerk anlegen</h3>

        <form class="needs-validation mt-4" action="{{ route('memos.store') }}" method="post" novalidate>
            @component('memo.fields', [ 'memo' => $memo, 'currentProject' => $currentProject, 'projects' => $projects, 'currentEmployeeComposer' => $currentEmployeeComposer, 'employees' => $employees, 'currentPersonRecipient' => $currentPersonRecipient, 'currentPresentPeople' => $currentPresentPeople, 'currentNotifiedPeople' => $currentNotifiedPeople, 'people' => $people ])
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
