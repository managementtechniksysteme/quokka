{{-- Cost-vs-estimate status arrow (success=under / warning=near / danger=over).
     Expects $status = 'success'|'warning'|'danger'|null. --}}
@if($status)
    <svg class="icon icon-12 text-{{ $status }}">
        @switch($status)
            @case('success')<use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>@break
            @case('warning')<use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down-right"></use>@break
            @case('danger')<use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>@break
        @endswitch
    </svg>
@endif
