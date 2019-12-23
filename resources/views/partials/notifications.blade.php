@if(Session::has('success'))
    <notification type="success" v-cloak>
        <div class="d-inline-flex align-items-center">
            <svg class="feather feather-24 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check"></use>
            </svg>
            {{ Session::get('success') }}
        </div>
    </notification>
@elseif(Session::has('info'))
    <notification type="info" v-cloak>
        <div class="d-inline-flex align-items-center">
            <svg class="feather feather-24 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
            </svg>
            {{ Session::get('info') }}
        </div>
    </notification>
@elseif(Session::has('warning'))
    <notification type="warning" v-cloak>
        <div class="d-inline-flex align-items-center">
            <svg class="feather feather-24 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
            </svg>
            {{ Session::get('warning') }}
        </div>
    </notification>
@elseif(Session::has('danger'))
    <notification type="danger" v-cloak>
        <div class="d-inline-flex align-items-center">
            <svg class="feather feather-24 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-octagon"></use>
            </svg>
            {{ Session::get('danger') }}
        </div>
    </notification>
@endif
