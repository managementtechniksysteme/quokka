{{-- Cost-vs-estimate status arrow (success=under / warning=near / danger=over).
     Expects $status = 'success'|'warning'|'danger'|null. --}}
@if($status)
    <svg class="icon-bs icon-12 text-{{ $status }}">
        @switch($status)
            @case('success')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use>@break
            @case('warning')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down-right"></use>@break
            @case('danger')<use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use>@break
        @endswitch
    </svg>
@endif
