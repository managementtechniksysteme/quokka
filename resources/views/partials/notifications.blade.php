@if(Session::has('success'))
    <notification type="success" v-cloak>
        <div class="d-inline-flex align-items-center">
            <svg class="icon-bs icon-24 me-2">
                <use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use>
            </svg>
            {{ Session::get('success') }}
        </div>
    </notification>
@elseif(Session::has('info'))
    <notification type="info" v-cloak>
        <div class="d-inline-flex align-items-center">
            <svg class="icon-bs icon-24 me-2">
                <use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use>
            </svg>
            {{ Session::get('info') }}
        </div>
    </notification>
@elseif(Session::has('warning'))
    <notification type="warning" v-cloak>
        <div class="d-inline-flex align-items-center">
            <svg class="icon-bs icon-24 me-2">
                <use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use>
            </svg>
            {{ Session::get('warning') }}
        </div>
    </notification>
@elseif(Session::has('danger'))
    <notification type="danger" v-cloak>
        <div class="d-inline-flex align-items-center">
            <svg class="icon-bs icon-24 me-2">
                <use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-octagon"></use>
            </svg>
            {{ Session::get('danger') }}
        </div>
    </notification>
@endif
