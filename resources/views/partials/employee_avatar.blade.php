{{-- Round identity avatar for an employee: the user's chosen colour + username
     initials, falling back to the person's hashed colour + name initials when the
     employee has no user account. Mirrors the navbar/comment avatar recipe.
     Params: $employee (required), $modifier (optional extra avatar classes). --}}
@php
    $avatarUser = $employee->user;
    $avatarHex = $avatarUser?->avatar_colour_hex;
@endphp
<span class="q-avatar q-avatar--round{{ isset($modifier) ? ' '.$modifier : '' }} @unless($avatarHex) q-avatar--{{ $employee->person->avatar_colour }} @endunless"@if($avatarHex) style="background: color-mix(in srgb, {{ $avatarHex }} 20%, transparent); color: {{ $avatarHex }};"@endif>{{ $avatarUser ? $avatarUser->username_avatar_string : $employee->person->initials }}</span>
